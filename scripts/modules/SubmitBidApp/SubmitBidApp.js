export default class SubmitBidApp {
	constructor(validator, form, bidDAO, uiManager) {
		this.ui = uiManager;
		this.validator = validator;
		this.formElement = form;
		this.auctionId = form.dataset.auctionId;
		this.bidInput = form.bidAmount;
		this.placeBidButton = form.submitBid;
		this.bidDAO = bidDAO;

		this.initPlaceBidForm();
	}

	initPlaceBidForm = () => {
		this.initPlaceBidButton();
		this.initPlaceBidInput();
	}

	initPlaceBidButton = () => {
		this.placeBidButton.addEventListener('click', (e) => {
			this.handleBidSubmit();
		});
	}

	initPlaceBidInput = () => {
		this.bidInput.addEventListener('keypress', (e) => {
			if(e.key != "Enter") {
				return;
			}

			e.preventDefault();

			this.handleBidSubmit();
		});

	}

	handleBidSubmit = () => {
		if(!this.bidInput.reportValidity()) {
			return;
		}

		const bidAmount = this.bidInput.value

		this.bidDAO.placeBid(this.auctionId, bidAmount)
			.then(data => {
				if(data.status !== "success") {
					throw new Error(data.message);
				}

				this.confirmBid(bidAmount);
			})
			.catch(error => {
				this.handleBidInsertError(error.message);
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