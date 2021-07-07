export default class StampeeApp {
	constructor(uiMgr, bidDAO, loginDAO) {
		this.ui = uiMgr;
		this.bidDAO = bidDAO;
		this.loginDAO = loginDAO;
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
						throw new Error(data.status);
					}
				})
				.catch(error => {
					// this.handleBidInsertError(error);
					console.log(error);
				});
		});
	}
}