const setMultiAttributes = (e, attrs) => {
	for(const a in attrs) {
		e.setAttribute(a, attrs[a]);
	}
}

const setMultiClasses = (e, classes) => {
	classes.forEach(cl => {
		e.classList.add(cl);
	})
}

export const buildTextElement = (type, text, attrs = null, classes = null) => {
	let e;

	e = document.createElement(type);

	if(attrs) {
		setMultiAttributes(e, attrs)
	}
	
	if(classes) {
		setMultiClasses(e, classes);
	}

	e.textContent = text;

	return e;
}

export const buildElement = (type, attrs = null, classes = null) => {
	const e = document.createElement(type);

	if(attrs) {
		setMultiAttributes(e, attrs)
	}

	if(classes) {
		setMultiClasses(e, classes);
	}

	return e;
}

export const buildButton = (type, label, attrs = null, classes = null) => {
	const b = document.createElement("button");

	b.setAttribute("type", type);

	if(attrs) {
		setMultiAttributes(b, attrs)
	}

	if(classes) {
		setMultiClasses(b, classes);
	}

	b.textContent = label;

	return b;
}

export const cleanElement = (e) => {
	while(e.lastElementChild) {
		e.lastElementChild.remove();
	}
}