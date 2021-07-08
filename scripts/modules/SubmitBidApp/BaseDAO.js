export default class BaseDAO {
	data = {
		method: "POST",
		headers: {
			"Content-type": "application/json; charset=utf-8"
		}
	}

	fetchStuff = async (gateway) => {
		return await fetch(gateway, this.data)
			.then(resp => resp.json());
	}
}