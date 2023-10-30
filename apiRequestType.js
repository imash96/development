// to create a account using /api/account

const data = {
    'account_name': 'LRBZ',
    'account_email': 'excentleathers@gmail.com',
    'seller_name': 'Chand Ahmed',
    'store_name': 'Leather Bazar',
    'contact_number': '9321293727',
    'status': 'Active'
}
const url = 'http://127.0.0.1:8000/api/account'
const res = await(await fetch(url, {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
})).json()

// update last scrap time
const url = 'http://127.0.0.1:8000/api/account/LRBZ'
const data = {
    'last_scrap': Date.now(),
    'isFirstScrap': false
}
const res = await(await fetch(url, {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
})).json()
// get account details or get last scrap time

const url = 'http://127.0.0.1:8000/api/account/LRBZ'
const res = await(await fetch(url)).json()
