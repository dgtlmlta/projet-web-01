export default class HiddenMenu {
	constructor(btn, p, cv) {
		this.button = btn;
		this.menuPanel = p;
		this.classVisible = cv;

		this.initButton();
	}

	initButton = () => {
		this.button.addEventListener('click', (e) => {
			this.showMenu();
		});
	}

	showMenu = () => {
		this.menuPanel.classList.toggle(this.classVisible);
	}

}