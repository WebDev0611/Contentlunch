launch.module.factory('WriterAccessService', function($resource) {

	var categories = $resource("/api/writeraccess/categories", null, {
		get: {
			method: "GET"
		}
	});

	var orders = $resource("/api/writeraccess/orders", null, {
		get: {
			method: "GET"
		}
	});

	var order = $resource("/api/writeraccess/order/:id", {id: "@id"}, {
		get: {
			method: "GET"
		}
	});

	var createOrder = $resource("/api/writeraccess/order/create", {hourstocomplete: "@hourstocomplete", writer: "@writer", wordcount: "@wordcount", title: "@title", instructions: "@instructions"}, {
		post: {
			method: "POST"
		}
	});

	var expertises = $resource("/api/writeraccess/expertises", null, {
		get: {
			method: "GET"
		}
	});

	var assetTypes = $resource("/api/writerAccessAssetTypes", null, {
		get: {
			method: "GET"
		}
	});

	return {
		categories: categories.get,
		orders: orders.get,
		order: order.post,
		createOrder: createOrder.post,
		expertises: expertises.get,
		assetTypes: assetTypes.get
	};

});
