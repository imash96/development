const ACCOUNT_NAME = 'LRBZ';
const SERVER_ADD = 'http://localhost:8000';

const getScrapDate = async () => {
    const res = await (await fetch(`${SERVER_ADD}/api/account/${ACCOUNT_NAME}`)).json();
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

async function initOrderDetails() {
    const lastScrap = await getScrapDate();
    const dateNow = Date.now();
    const scrapDate = {}
    if (lastScrap) {
        scrapDate.startTime = lastScrap
        scrapDate.endTime = dateNow
    } else {
        scrapDate.startTime = new Date('01 Jan 2015').getTime()
        scrapDate.endTime = dateNow
    }
    const data = await getOrderList();
    const orders = data['orders'];
    for (let i = 0; i < orders.length; i++) {
        const orderDetails = await getOrderDetails(orders[i]['amazonOrderId']);
        const order = orderDetails['order'];
        if (updatedOrderDetail['salesChannel'] == 'Non-Amazon') {
            continue;
        }
        const amazonOrderId = orderDetails.amazonOrderId;
        const orderAddress = await getOrderAdd(amazonOrderId, order.blob);
        try {
            order.addresses = orderAddress[amazonOrderId]['address'];
        } catch {
            order.addresses = null
            console.log(`Address not found order id: ${amazonOrderId}`)
        }
        const customOrder = {}
        const { buyerName, buyerPONumber, buyerVatNumber, buyerLegalName, buyerCompanyName, badges, verifiedBusinessBuyer, buyerProxyEmail } = order
        const customer = { buyerName, buyerPONumber, buyerVatNumber, buyerLegalName, buyerCompanyName, badges, verifiedBusinessBuyer, buyerProxyEmail }
        customOrder.customer = customer
    }
    // ---------------------------------------------------------------------
    const updateScrap = await updateScrapDate({
        'last_scrap': dateNow,
        'isFirstScrap': false
    });
    return scrapDate
}

const scrapDate = await initOrderDetails();