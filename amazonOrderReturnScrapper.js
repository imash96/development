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

const flatJson = (objs, initial = "") => {
    var toReturn = {};

    for (var obj in objs) {
        if (!objs.hasOwnProperty(obj)) continue;
        if ((typeof objs[obj]) == 'object' && objs[obj] !== null && !Array.isArray(objs[obj])) {
            var flatObject = flatJson(objs[obj]);
            for (var ob in flatObject) {
                if (!flatObject.hasOwnProperty(ob)) continue;
                toReturn[initial + obj + '_' + ob] = flatObject[ob];
            }
        } else {
            toReturn[initial + obj] = objs[obj];
        }
    }
    return toReturn;
}

const sleep = async (ms) => {
    return new Promise((accept) => {
        setTimeout(() => {
            accept();
        }, ms);
    });
}

const getIndex = (str, ind) => {
    idx = str.indexOf(" ", ind) > 35 ? getIndex(str, ind - 5) : str.indexOf(" ", ind);
    return idx
}

const stringOp = (str) => {
    if (str === null) return null;
    str = str.normalize("NFD").replace(/\p{Diacritic}/gu, "");
    str = str.toLowerCase().split(' ');
    for (var i = 0; i < str.length; i++) {
        str[i] = str[i].charAt(0).toUpperCase() + str[i].slice(1);
    }
    return str.join(' ');
}

const getCommission = (amount, code) => {
    if (code == 'USD')
        return amount * 0.175;
    return amount * 0.165
}

const getINR = (amount, code) => {
    INR = currencyDetails.rates.INR - 2;
    if (code == 'USD')
        return amount * INR;
    code = currencyDetails.rates[code];
    return (amount / code * INR)
}

const getCurrencyDetails = async () => {
    return await (await fetch(`https://openexchangerates.org/api/latest.json?app_id=35ee82c2a6574e4cb04b54e4c4c68da4&symbols=CAD,MXN,INR,EUR,GBP&prettyprint=0`)).json();
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
            customOrder.addresses = { addressId, messageStr, addressType, companyName, city, county, municipality, postalCode, countryCode, countryLine, phoneNumber, label, confidentialVisibleAddress }
            customOrder.addresses.name = stringOp(name)
            customOrder.addresses.line1 = stringOp(line1)
            customOrder.addresses.line2 = stringOp(line2)
            customOrder.addresses.line3 = stringOp(line3)
            const trimAdd = (id) => {
                if (id > 3) return;
                if (customOrder.addresses['line' + id] == null) return;
                if (customOrder.addresses['line' + id].length > 35) {
                    idx = getIndex(customOrder.addresses['line' + id], customOrder.addresses['line' + id].lastIndexOf(" "))
                    temp = customOrder.addresses['line' + id].substr(idx + 1);
                    customOrder.addresses['line' + id] = customOrder.addresses['line' + id].substr(0, idx);
                    if (customOrder.addresses['line' + (id + 1)] != null) {
                        customOrder.addresses['line' + (id + 1)] = temp + " " + customOrder.addresses['line' + (id + 1)];
                        trimAdd(id + 1)
                    } else {
                        customOrder.addresses['line' + (id + 1)] = temp;
                    }
                }
            }
            trimAdd(1);
        }

        {
            const { amazonOrderId, cancellationDate, earliestDeliveryDate, earliestShipDate, latestDeliveryDate, latestShipDate, purchaseDate, fulfillmentChannel, iossNumber, isBuybackOrder, isIBAOrder, marketplaceTaxingSeller, orderChannel, orderMarketplaceId, orderType, paymentMethodDetails, possibleCancelReasons, replacedOrderId, rootMarketplaceId, salesChannel, salesChannelFlagUrl, sellerNotes, sellerOrderId, shippingService, taxCollectionModel, taxResponsiblePartyName } = order

            const { OrderStatus, RefundApplied, RefundPending, AtRiskOfCancellation, Late: isLate } = order.orderStatus

            customOrder = { ...customOrder, amazonOrderId, cancellationDate, earliestDeliveryDate, earliestShipDate, latestDeliveryDate, latestShipDate, purchaseDate, fulfillmentChannel, iossNumber, isBuybackOrder, isIBAOrder, marketplaceTaxingSeller, orderChannel, orderMarketplaceId, orderType, paymentMethodDetails, possibleCancelReasons, replacedOrderId, rootMarketplaceId, salesChannel, salesChannelFlagUrl, sellerNotes, sellerOrderId, shippingService, taxCollectionModel, taxResponsiblePartyName, OrderStatus, RefundApplied, RefundPending, AtRiskOfCancellation, isLate }
        }

        {
            const { orderCost } = order;
            const { GrandTotal_Amount, GrandTotal_CurrencyCode: CurrencyCode, NetSalesProceeds_Amount, PromotionTotal_Amount, PromotionTotalTax_Amount, RefundTotal_Amount, ShippingTotal_Amount, ShippingTotalTax_Amount, TaxTotal_Amount, Total_Amount } = flatJson(orderCost);
            customOrder.order_cost = { GrandTotal_Amount, CurrencyCode, NetSalesProceeds_Amount, PromotionTotal_Amount, PromotionTotalTax_Amount, RefundTotal_Amount, ShippingTotal_Amount, ShippingTotalTax_Amount, TaxTotal_Amount, Total_Amount }
            const Commission_Amount = getCommission(GrandTotal_Amount, CurrencyCode).toFixed(2);
            customOrder.order_cost.Commission_Amount = Commission_Amount;
            customOrder.order_cost.INR_Amount = getINR(Total_Amount - Commission_Amount, CurrencyCode).toFixed(2);
        }

        customOrder.order_items = [];
        for (let j = 0; j < order.orderItems.length; j++) {
            let temp_item = {}
            const order_item = order.orderItems[j];
            {
                const { OrderItemId, isBuyerRequestedCancel, cancelReason, CustomerOrderItemCode, Gift, GiftMessage, GiftWrapType, ImageSourceSarek, ItemCustomizations, ProductCustomizations, QuantityCanceled, QuantityOrdered, QuantityShipped, QuantityUnshipped, SignatureRecommended } = order_item
                const { ItemStatus, CancelStatus } = order_item.ItemStatus;
                temp_item = { OrderItemId, isBuyerRequestedCancel, cancelReason, ItemStatus, CustomerOrderItemCode, Gift, GiftMessage, GiftWrapType, ImageSourceSarek, ItemCustomizations, CancelStatus, ProductCustomizations, QuantityCanceled, QuantityOrdered, QuantityShipped, QuantityUnshipped, SignatureRecommended }
            }

            {
                const { ItemCost } = order_item;
                const { NetSalesProceeds_Amount, CurrencyCode, Promotion_Amount, PromotionTax_Amount, Refund_Amount, Shipping_Amount, ShippingTax_Amount, Subtotal_Amount, SubtotalTax_Amount, Tax_Amount, Total_Amount, UnitPrice_Amount } = flatJson(ItemCost);
                temp_item.item_cost = { NetSalesProceeds_Amount, CurrencyCode, Promotion_Amount, PromotionTax_Amount, Refund_Amount, Shipping_Amount, ShippingTax_Amount, Subtotal_Amount, SubtotalTax_Amount, Tax_Amount, Total_Amount, UnitPrice_Amount };
            }

            {
                const { ASIN, Condition, ImageUrl, HarmonizedCode, LegacyListingId, ProductLink, SellerSKU, Title, transparencyItem } = order_item;
                temp_item.product = { ASIN, Condition, ImageUrl, HarmonizedCode, LegacyListingId, ProductLink, SellerSKU, Title, transparencyItem }
                const tempId = temp_item.product.ImageUrl.split(".")[2].split('/')[3];
                temp_item.product.ImageUrl = `https://m.media-amazon.com/images/I/${tempId}._AC_800.jpg`;
            }

            customOrder.order_items.push(temp_item);
        }

        customOrder.packages = [];
        for (let j = 0; j < order.packages.length; j++) {
            let temp_package = {}
            const package = order.packages[j];
            {
                const { Carrier, IsSignatureConfirmationApplied, ShipmentId, ShipDate, ShippingService, TrackingId } = package
                temp_package = { Carrier, IsSignatureConfirmationApplied, ShipmentId, ShipDate, ShippingService, TrackingId }
            }
            customOrder.packages.push(temp_package)
        }

        // ---------------
        console.log(customOrder)
        listOrders.orders.push(customOrder);
        await sleep(10);
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

const currencyDetails = await getCurrencyDetails();
await initOrderDetails();