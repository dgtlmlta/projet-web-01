import { isSizeOrHigher } from "./modules/navigatorDetection.js";


if (isSizeOrHigher("990px")){
	const options = {
		fillContainer: true,
		offset: {
			vertical: 0,
			horizontal: getComputedStyle(document.documentElement,null).getPropertyValue("--grid-gap")
		}
	}
	
	const zoomer = new ImageZoom(
		document.querySelector('.panel-main-image picture'),
		options
	);
}