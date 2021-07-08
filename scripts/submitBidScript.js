import SubmitBidApp from "/scripts/modules/SubmitBidApp/SubmitBidApp.js";
import BidDAO from "/scripts/modules/SubmitBidApp/BidDAO.js";
import BidFieldValidator from "/scripts/modules/SubmitBidApp/BidFieldValidator.js";

const
	submitButton = document.forms.biddingForm.submitBid,
	bidInput = document.forms.biddingForm.bidAmount,
	
	bidFieldValidator = new BidFieldValidator(bidInput),
	bidDAO = new BidDAO(),
	bidApp = new SubmitBidApp(bidFieldValidator, bidInput, submitButton, bidDAO);