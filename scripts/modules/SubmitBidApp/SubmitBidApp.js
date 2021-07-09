export default class SubmitBidApp {
	constructor(validator, form, bidDAO, uiManager) {
		this.ui = uiManager;
		this.validator = validator;
		this.bidInput = form.bidAmount;
		this.submitButton = form.submitBid;
		this.bidDAO = bidDAO;

		this.initFormPlaceBidButton(this.submitButton);
	}

	initFormPlaceBidButton = (button) => {
		button.addEventListener('click', (e) => {
			const
				auctionId = e.target.dataset.auctionId;
			
			if(!this.bidInput.reportValidity()) {
				console.log("trop bas");
				return;
			}

			const bidAmount = this.bidInput.value

			this.bidDAO.placeBid(auctionId, bidAmount)
				.then(data => {
					if(data.status !== "success") {
						throw new Error(data.message);
					}

					this.confirmBid(bidAmount);
				})
				.catch(error => {
					this.handleBidInsertError(error.message);
				});
		});
	}

	confirmBid = (newAmount) => {
		this.bidInput.value = "";
		this.validator.drawMessage("Votre mise a été reçue, merci !");
		this.validator.updateMinAmount(newAmount);
		this.ui.updateCurrentBid(newAmount);
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