import HiddenMenu from "./modules/HiddenMenus.js";


const hiddenMenuFilters = new HiddenMenu(
	document.querySelector('.show-filters-button > button'),
	document.querySelector('.filters-bar'),
	"show-filters-bar"
);