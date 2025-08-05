class MultiSelectorAPI {
	constructor(container, options = [], config = {}) {
		this.el = typeof container === 'string' ? document.querySelector(container) : container;
		this.options = options;
		this.selected = new Map();
		this.selectedArray = [];
		this.lastFetchKeyword = '';

		this.config = Object.assign({
			name: 'selected_ids',
			label: 'Select Items',
			placeholder: 'Type to search...',
			fetchUrl: null, // e.g. "/api/items?q="
			debounceTime: 300,
			single: false,
			onChoose: null,
			parseResponse: (data) => data // customize how API response is parsed
		}, config);

		this.debounceTimeout = null;

		this.buildDOM();
		this.bindEvents();
	}

	buildDOM() {
		this.el.classList.add(
			'multi-select',
			'group',
			'border',
			'rounded-lg',
			'p-2',
			'relative',
			'focus-within:border-primary'
		);

		this.el.innerHTML = `
            <label class="text-slate-500 group-focus-within:text-primary text-xs absolute top-2 left-2">
                ${this.config.label}
            </label>
            <div class="selected-tags flex flex-wrap items-center gap-1 mt-6 mb-1 text-sm text-slate-700 p-1">
                <input type="text" class="multi-select-input flex-1 min-w-[100px] outline-none bg-transparent text-sm text-slate-700" placeholder="${this.config.placeholder}" autocomplete="off">
            </div>
            <ul class="multi-select-dropdown absolute z-10 left-0 right-0 bg-white border rounded-md mt-1 hidden max-h-40 overflow-auto shadow-md text-sm text-slate-700"></ul>
            <input type="hidden" class="multi-select-hidden" name="${this.config.name}">
        `;

		this.input = this.el.querySelector('.multi-select-input');
		this.dropdown = this.el.querySelector('.multi-select-dropdown');
		this.selectedWrapper = this.el.querySelector('.selected-tags');
		this.hiddenInput = this.el.querySelector('.multi-select-hidden');
	}

	bindEvents() {
		this.input.addEventListener('input', () => {
			clearTimeout(this.debounceTimeout);
			this.debounceTimeout = setTimeout(() => this.onSearch(), this.config.debounceTime);
		});

		document.addEventListener('click', (e) => {
			if (!this.el.contains(e.target)) {
				this.dropdown.classList.add('hidden');
			}
		});
	}

	async onSearch() {
		const keyword = this.input.value.trim().toLowerCase();
		if (keyword === this.lastFetchKeyword) return;
		this.lastFetchKeyword = keyword;

		if (this.config.fetchUrl) {
			try {
				const res = await fetch(`${this.config.fetchUrl}${encodeURIComponent(keyword)}`);
				if (!res.ok) throw new Error('API Error');
				const data = await res.json();
				const parsed = this.config.parseResponse(data);

				const matches = parsed.filter(opt =>
					!this.selected.has(opt.id)
				);
				this.renderDropdown(matches);
			} catch (err) {
				console.error('MultiSelector fetch error:', err);
				this.renderDropdown([]);
			}
		} else {
			const matches = this.options.filter(opt =>
				opt.name.toLowerCase().includes(keyword) && !this.selected.has(opt.id)
			);
			this.renderDropdown(matches);
		}
	}

	renderDropdown(matches) {
		this.dropdown.innerHTML = '';
		if (matches.length === 0) {
			this.dropdown.classList.add('hidden');
			return;
		}

		matches.forEach(opt => {
			const li = document.createElement('li');
			li.textContent = opt.name;
			li.className = 'px-3 py-1 hover:bg-slate-100 cursor-pointer';
			li.onclick = () => this.selectItem(opt);
			this.dropdown.appendChild(li);
		});

		this.dropdown.classList.remove('hidden');
	}

	selectItem(opt) {
		if (!this.selected.has(opt.id)) {
			this.selected.set(opt.id, opt.name);
			this.selectedArray.push(opt);
			this.input.value = '';
			this.updateTags();
			this.updateHiddenInput();
			this.dropdown.classList.add('hidden');

			if (this.config.onChoose !== null) {
				this.config.onChoose(Array.from(this.selectedArray));
			}

			if (this.config.single) {
				this.input.classList.add('hidden');
			}
		}
	}

	updateTags() {
		const inputEl = this.input;
		this.selectedWrapper.innerHTML = '';
		this.selectedWrapper.appendChild(inputEl);

		this.selected.forEach((name, id) => {
			const tag = document.createElement('span');
			tag.className = 'flex items-center gap-1 bg-primary text-white text-sm px-3 py-1 rounded-full';

			const label = document.createElement('span');
			label.textContent = name;

			const remove = document.createElement('ion-icon');
			remove.setAttribute('name', 'close-circle');
			remove.className = 'text-white text-lg cursor-pointer';
			remove.onclick = () => {
				this.selected.delete(id);
				this.updateTags();
				this.updateHiddenInput();

				if (this.config.single) {
					this.input.classList.remove('hidden');
				}
			};

			tag.appendChild(label);
			tag.appendChild(remove);
			this.selectedWrapper.insertBefore(tag, inputEl);
		});
	}

	updateHiddenInput() {
		this.hiddenInput.value = JSON.stringify(Array.from(this.selected.keys()));
	}
}
