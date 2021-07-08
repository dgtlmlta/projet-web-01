export default class SubmitBidApp {
	constructor(validator, bidInput, submitButton, bidDAO) {
		this.validator = validator;
		this.bidInput = bidInput;
		this.submitButton = submitButton;
		this.bidDAO = bidDAO;

		this.initFormPlaceBidButton(this.submitButton);
	}

	initFormPlaceBidButton = (button) => {
		button.addEventListener('click', (e) => {
			const
				amountField = e.target.closest("form").bidAmount,
				auctionId = e.target.dataset.auctionId;
			
			if(!amountField.reportValidity()) {
				return;
			}

			this.bidDAO.placeBid(auctionId, amountField.value)
				.then(data => {
					if(data.status !== "success") {
						throw new Error(data.message);
					}

					this.confirmBid();
				})
				.catch(error => {
					this.handleBidInsertError(error.message);
				});
		});
	}

	confirmBid = () => {
		this.validator.drawMessage("Votre mise a été reçue, merci !")
	}

	handleBidInsertError = (message) => {
		this.validator.throwError(this.getBidInsertError(message));

		return this;
	}

	getBidInsertError = (message) => {
		console.log(message);
		return this.validator.errorDictionnary[message];
	}
}