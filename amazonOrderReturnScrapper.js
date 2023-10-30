const ACCOUNT_NAME = 'LRLF';
const SERVER_ADD = 'https://e365mail.com/index.php';

const getScrapDate = async () => {
    const res = await (await fetch(`${SERVER_ADD}/api/account/${ACCOUNT_NAME}`, {
        headers: {
            'mode': 'no-cors',
        },
    })).json();
    if (res.isFirstScrap) {
        return 0;
    }
    return res['last_scrap'];
}

const updateScrapDate = async (data) => {
    const res = await (await fetch(`${SERVER_ADD}/api/account/${ACCOUNT_NAME}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            'mode': 'no-cors',
        },
        body: JSON.stringify(data),
    })).json();
}

const getOrderList = async (scrapDate) => {
    return await (await fetch(`https://sellercentral.amazon.com/orders-api/search?limit=1500&offset=0&sort=order_date_desc&date-range=${scrapDate.startTime}-${scrapDate.endTime}&fulfillmentType=&orderStatus=&forceOrdersTableRefreshTrigger=true`)).json();
}

const getOrderDetails = async (orderId) => {
    return await (await fetch(`https://sellercentral.amazon.com/orders-api/order/${orderId}`)).json();
}

const getOrderAdd = async (orderId, blob) => {
    var body = `{\"blobs\":[\"${blob}\"]}`;
    return await (await fetch('https://sellercentral.amazon.com/orders-st/resolve', {
        method: 'POST',
        headers: {
            "content-type": "application/json",
            "accept": "application/json",
            "redirect": "manual",
            "referer": `https://sellercentral.amazon.com/orders-v3/order/${orderId}`,
        },
        body: body,
    })).json();
}

async function initOrderDetails() {
    const lastScrap = await getScrapDate();
    const dateNow = Date.now();
    const scrapDate = {}
    if (lastScrap) {
        scrapDate.startTime = lastScrap
        scrapDate.endTime = dateNow
    } else {
        // scrapDate.startTime = new Date('01 Jan 2015').getTime()
        scrapDate.startTime = new Date('01 Sept 2023').getTime()
        scrapDate.endTime = dateNow
    }
    const data = await getOrderList(scrapDate);
    const orders = data['orders'];
    for (let i = 0; i < orders.length; i++) {
        const orderDetails = await getOrderDetails(orders[i]['amazonOrderId']);
        const order = orderDetails.order;
        if (order['salesChannel'] == 'Non-Amazon') {
            continue;
        }
        const amazonOrderId = order.amazonOrderId;
        const { blob } = order;
        const orderAddress = await getOrderAdd(amazonOrderId, blob);
        const addresses = orderAddress[amazonOrderId]['address'];
        let customOrder = {}
        {
            const { buyerPONumber, buyerCompanyName, badges, verifiedBusinessBuyer, buyerProxyEmail, addressType } = order
            const { buyerName, buyerVatNumber, buyerLegalName, } = orderAddress[amazonOrderId]
            const { addressId, messageStr, name, companyName, line1, line2, line3, city, county, municipality, postalCode, countryCode, countryLine, phoneNumber, label, confidentialVisibleAddress } = addresses
            customOrder.customer = { buyerName, buyerPONumber, buyerVatNumber, buyerLegalName, buyerCompanyName, badges, verifiedBusinessBuyer, buyerProxyEmail }
            customOrder.addresses = { addressId, messageStr, addressType, name, companyName, line1, line2, line3, city, county, municipality, postalCode, countryCode, countryLine, phoneNumber, label, confidentialVisibleAddress }
        }

        {
            const { amazonOrderId, cancellationDate, earliestDeliveryDate, earliestShipDate, latestDeliveryDate, latestShipDate, purchaseDate, fulfillmentChannel, iossNumber, isBuybackOrder, isIBAOrder, marketplaceTaxingSeller, orderChannel, orderMarketplaceId, orderType, paymentMethodDetails, possibleCancelReasons, replacedOrderId, rootMarketplaceId, salesChannel, salesChannelFlagUrl, sellerNotes, sellerOrderId, shippingService, taxCollectionModel, taxResponsiblePartyName } = order

            const { OrderStatus, RefundApplied, RefundPending, AtRiskOfCancellation, Late: isLate } = order.orderStatus

            customOrder = { ...customOrder, amazonOrderId, cancellationDate, earliestDeliveryDate, earliestShipDate, latestDeliveryDate, latestShipDate, purchaseDate, fulfillmentChannel, iossNumber, isBuybackOrder, isIBAOrder, marketplaceTaxingSeller, orderChannel, orderMarketplaceId, orderType, paymentMethodDetails, possibleCancelReasons, replacedOrderId, rootMarketplaceId, salesChannel, salesChannelFlagUrl, sellerNotes, sellerOrderId, shippingService, taxCollectionModel, taxResponsiblePartyName, OrderStatus, RefundApplied, RefundPending, AtRiskOfCancellation, isLate }
        }

        {
            const { orderCost } = order
        }

        // ---------------
        console.log(customOrder)
        listOrders.orders.push(customOrder)
    }
    // ---------------------------------------------------------------------
    const updateScrap = await updateScrapDate({
        'last_scrap': dateNow,
        'isFirstScrap': false
    });
    return scrapDate
}

const listOrders = {
    type: "amazon",
    sellerName: ACCOUNT_NAME,
    orders: []
}
await initOrderDetails();