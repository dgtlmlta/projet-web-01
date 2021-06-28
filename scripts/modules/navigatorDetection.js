export const isTouchEnabled = () => {
	return matchMedia('(hover: none)').matches;
}

export const isSizeOrLower = (s) => {
	return matchMedia(`(max-width: ${s})`).matches;
}

export const isSizeOrHigher = (s) => {
	return matchMedia(`(min-width: ${s})`).matches;
}