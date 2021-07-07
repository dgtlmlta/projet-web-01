import StampeeApp from "/scripts/modules/StampeeApp/StampeeApp.js";
import BidDAO from "/scripts/modules/StampeeApp/BidDAO.js";
// import StampeeApp from "/scripts/modules/StampeeApp/LoginDAO.js";

import FieldValidator from "/scripts/modules/FieldValidator.js";

const
	bidDAO = new BidDAO(),
	app = new StampeeApp(null, bidDAO, null);

if(document.forms.biddingForm.submitBid) {
	const
		button = document.forms.biddingForm.submitBid,
		bidField = document.forms.biddingForm.bidAmount;

	console.log(bidField);
	app.initFormPlaceBidButton(button);

	const fieldValidator = new FieldValidator(bidField);
}