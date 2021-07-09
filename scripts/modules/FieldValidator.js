import { buildTextElement } from "./DOMBuilder.js";

export default class FieldValidator {
	
	fieldBlockClass = ".input-field";
	messageElement;
	hasError = false;

	constructor(f) {
		this.field = f;
		this.fieldBlock = this.field.closest(this.fieldBlockClass);
		
		this.init();
	}

	init = () => {
		this.field.addEventListener('invalid', e => {
			e.preventDefault();
			this.throwError();
		});
	}

	throwError = (customMessage = null) => {
		this.hasError = true;

		// Basculer la classe d'erreur
		this.fieldBlock.classList.toggle("error", this.hasError);

		// Créer le message d'erreur
		const errMsg = customMessage ?? this.getError(this.field)			

		this.messageElement = this.drawMessage(errMsg);
	}

	drawMessage = (message) => {
		if(this.messageElement)
			this.cleanMessage();
		
		const msgElement = buildTextElement("small", message);
		// Injecter le message d'erreur
		this.fieldBlock.append(msgElement);
		this.messageElement = msgElement;

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

	cleanMessage = () => {
		this.fieldBlock.classList.toggle("error", !this.hasError);		
		this.messageElement.remove();
	}
}