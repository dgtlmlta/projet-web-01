export default class SearcBoxApp {
	baseUrl = "/catalogue/recherche/";

	constructor(boxElement) {
		this.boxElement = boxElement;
		this.button = boxElement.querySelector('button');
		this.input = boxElement.querySelector('input');

		this.initButton();
		this.initEnterKey();
	}

	initButton = () => {
		this.button.addEventListener('click', (e) => {
			this.routeSearch(this.input.value);
		});
	}

	initEnterKey = () => {
		this.input.addEventListener('keypress', (e) => {
			if(e.key == "Enter") {
				this.routeSearch(this.input.value);
			}
		});
	}

	routeSearch = ($searchString) => {
		window.location = `${this.baseUrl}${encodeURIComponent($searchString)}`;
	}
}