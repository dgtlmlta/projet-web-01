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
			window.location = `${this.baseUrl}${encodeURIComponent(this.input.value)}`;
		});
	}

	initEnterKey = () => {
		this.input.addEventListener('keypress', (e) => {
			if(e.key == "Enter") {
				window.location = `${this.baseUrl}${encodeURIComponent(this.input.value)}`;
			}
		});
	}
}