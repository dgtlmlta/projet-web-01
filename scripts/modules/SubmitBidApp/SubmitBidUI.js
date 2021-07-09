export default class SubmitBidUI {
	constructor(panelBidding) {
		this.panel = panelBidding;
		this.currentBidDisplayElement = panelBidding.querySelector('.current-bid > .bid');
	}
	
	updateCurrentBid = (newAmount) => {
		const formattedAmount = new Intl.NumberFormat("fr-CA", { style: 'currency', currency: 'CAD' }).format(+newAmount);

		this.currentBidDisplayElement.textContent = `${formattedAmount}CA`;
	}
}