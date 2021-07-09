import FieldValidator from "/scripts/modules/FieldValidator.js";

export default class BidFieldValidator extends FieldValidator {
	errorDictionnary = {
		"not logged in" : "Vous devez vous authentifier afin de miser",
		"amount too low" : "Votre mise est inférieure à la mise courante",
		"empty amount" : "Vous devez inscrire une mise avant de soumettre"
	}

	constructor(field) {
		super(field);
	}

	updateMinAmount = (newAmount) => {
		this.field.setAttribute("min", newAmount);
	}
}