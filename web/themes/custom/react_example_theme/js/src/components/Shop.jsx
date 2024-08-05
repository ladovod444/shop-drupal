import React, { useEffect, useState } from 'react';
import {getOauth} from '../oauth'
import { API_PRODUCTS, APP_TEST, SHOP_URL } from '../config';
import GoodsList from './GoodsList';
function Shop() {

  const [goods, setGoods] = useState([]);

  const [error, setError] = useState(null)
  const [loading, setLoading] = useState(true);

  //console.log('SHOP_URL + API_PRODUCTS= ', process.env.REACT_APP_SHOP_URL)
  //console.log('SHOP_URL + API_PRODUCTS= ', SHOP_URL + API_PRODUCTS)

  //console.log(API_PRODUCTS);
  //console.log(REACT_APP_API_KEY);
  //console.log(APP_TEST);

  const getAllGoods = async (data) => {
    const response = await fetch(SHOP_URL + API_PRODUCTS, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + data.access_token
      },
    });
    if (!response.ok) {
      //const message = `An error has occured: ${response.status}`;
      const message = 'Error while loading products';
      throw new Error(message);
    }
    return await response.json();
  }

  useEffect(() => {
    getOauth().then(
      data => {
        //console.log(data)
        getAllGoods(data).then(
          data => {
            //setGoods(data)
            //console.log(data);
            setLoading(false);

            const few_goods = data.slice(1, 5);
            setGoods(few_goods)

            // setFilteredGoods(search ?
            //   data.filter(item =>
            //     item.displayName
            //       .toLowerCase()
            //       .includes(search.split('=')[1].toLowerCase())
            //   ) : data);
          }
        ).catch(error => {
          setError(error)
          //console.log(error.message); // 'An error has occurred: 404'
        });

      }
    ).catch(error => {
      setError(error)
      console.log(error.message); // 'An error has occurred: 404'
    });

    // }, [search]);
  }, []);

  return <div className="proj">
    {loading ? <div> Loading ...</div> :
      // goods.map(good =>
      //   <h5>{good.displayName}</h5>
      // )
      <GoodsList goods={goods} />
    }

  </div>
}

export default Shop
