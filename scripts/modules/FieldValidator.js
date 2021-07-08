import { buildTextElement } from "./DOMBuilder.js";

export default class FieldValidator {
	
	fieldBlockClass = ".input-field";
	errorMsgElement;
	hasError = false;

	constructor(f) {
		this.field = f;
		this.fieldBlock = this.field.closest(this.fieldBlockClass);
		
		this.init();
	}

	init = () => {
		this.field.addEventListener('invalid', e => {
			e.preventDefault();
			if(this.hasError)
				this.cleanErrors();
			this.throwError();
		});
	}

	throwError = (customMessage = null) => {
		this.hasError = true;

		// Basculer la classe d'erreur
		this.fieldBlock.classList.toggle("error");

		// Créer le message d'erreur
		const errMsg = customMessage ?? this.getError(this.field)			

		this.errorMsgElement = this.drawMessage(errMsg);
	}

	drawMessage = (message) => {
		const msgElement = buildTextElement("small", message);
		// Injecter le message d'erreur
		this.fieldBlock.append(msgElement);

		return msgElement;
	}

	getError = (i) => {
		if(i.validity.valueMissing) 
			return `Veuillez entrer une mise`;

		if(i.validity.typeMismatch) 
			return `Le champ n'est pas du bon format`;
		
		if(i.validity.patternMismatch) 
			return `Le champ n'est pas du bon format`;

		if(i.validity.rangeOverflow) 
			return `Le nombre de joueurs ne doit pas dépasser ${i.getAttribute("max")}`;

		if(i.validity.rangeUnderflow) 
			return `La  mise ne doit pas être inférieure à ${i.getAttribute("min")} $`;
	}

	cleanErrors = () => {
		this.fieldBlock.classList.toggle("error");
		
		if(this.errorMsgElement)
			this.errorMsgElement.remove();
	}
}