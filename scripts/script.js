import ClickMenu from "./modules/ClickMenu.js";
import SearcBoxApp from "/scripts/modules/SearchBoxApp/SearchBoxApp.js";

const
	clickNav = new ClickMenu(document.querySelector('.click-menu')),
	clickNavMobile = new ClickMenu(document.querySelector('.primary-navigation-mobile .click-menu'));

// Initialiser les champs de recherches.
for(const searchBoxElement of document.querySelectorAll('.search-box')) {
	new SearcBoxApp(searchBoxElement);
}
