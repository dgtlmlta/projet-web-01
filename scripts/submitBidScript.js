import SubmitBidApp from "/scripts/modules/SubmitBidApp/SubmitBidApp.js";
import BidDAO from "/scripts/modules/SubmitBidApp/BidDAO.js";
import BidFieldValidator from "/scripts/modules/SubmitBidApp/BidFieldValidator.js";
import SubmitBidUI from "/scripts/modules/SubmitBidApp/SubmitBidUI.js";

const
	bidForm = document.forms.biddingForm,
	bidPanel = document.querySelector('.panel-bidding'),
		
	bidFieldValidator = new BidFieldValidator(bidForm.bidAmount),
	bidDAO = new BidDAO(),
	bidUi = new SubmitBidUI(bidPanel),
	bidApp = new SubmitBidApp(bidFieldValidator, bidForm, bidDAO, bidUi);