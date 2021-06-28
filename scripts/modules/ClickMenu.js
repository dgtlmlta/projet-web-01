import { isTrueSet } from "./logicalFunctions.js";

/**
 * 
 * Classe permettant la gestion d'un menu à clicks
 * 
 */
export default class ClickMenu {
	
	// Propriété contenant le panneau présentement ouvert
	currentOpenPanel = null;

	constructor(e) {
		// Récupérer l'élément racine du menu ( <ul> )
		this.menuNode = e;

		// Récupérer tous les panneaux extensibles
		this.panels = this.menuNode.querySelectorAll('[data-menu-panel]');

		// Initialiser tous les boutons
		this.initAllButtons();

		// Initialiser l'écouteur de click sur l'ensemble du document
		this.initDocument();
	}

	initAllButtons = () => {
		Array.from(this.panels).forEach(e => {
			this.initButton(this.getPanelButton(e));
		});

	}

	initButton = (b) => {
		b.addEventListener('click', (e) => {
			const panel = e.target.closest("li");
			
			this.toggleMenu(panel);
		});
	}

	initDocument = () => {
		document.addEventListener('click', (e) => {
			const hit = e.target;
			

			if(hit.parentElement !== this.currentOpenPanel && !hit.closest("[aria-expanded]")) {
				this.toggleMenu(this.currentOpenPanel);
			}
		});
	}

	/**
	 * 
	 * Obtenir le bouton ( <button> ) d'action d'un panneau interactif
	 * 
	 * @param {Node} e Élément panneau duquel on obtient le bouton
	 */
	getPanelButton = (e) => {
		return e.querySelector('button');
	}

	/**
	 * 
	 * Obtenir le menu ( <ul> ) d'un panneau interactif
	 * 
	 * @param {Node} e Élément panneau duquel on obtient le bouton
	 */
	getPanelMenu = (e) => {
		return e.querySelector('[aria-hidden]');
	}

	/**
	 * 
	 * Afficher le panneau sélectionné
	 * 
	 * @param {Node} panel Panneau à afficher
	 */
	toggleMenu = (panel) => {

		// Si un panneau est ouvert, le fermer avant tout
		if(this.currentOpenPanel)
			this.toggleAria(this.currentOpenPanel);

		if(panel === this.currentOpenPanel) {
			this.currentOpenPanel = null
			return;
		}

		this.toggleAria(panel);

		this.currentOpenPanel = panel;
	}

	toggleAria = (e) => {
		// Récupérer la valeur de l'attribut aria-expanded pour connaitre la visibilité du panneau
		const isExpanded = isTrueSet(e.getAttribute("aria-expanded"));
		
		// Inverser les valeurs des deux attributs d'affichage
		e
			.querySelector('[aria-hidden]')
			.setAttribute("aria-hidden", isExpanded );

		e.setAttribute("aria-expanded", !isExpanded);
	}
}