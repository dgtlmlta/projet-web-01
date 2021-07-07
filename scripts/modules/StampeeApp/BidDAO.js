import BaseDAO from "./BaseDAO.js";

export default class BidDAO extends BaseDAO {
	
	placeBid = (auctionId, amount) => {
		this.data.body = JSON.stringify({
			payload: {
				auctionId: auctionId,
				amount: amount
			}
		});

		return this.fetchStuff("/mise/ajouter");
	}
}