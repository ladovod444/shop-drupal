import React from 'react';
import GoodsItem from "./GoodsItem";

function GoodsList(props) {
    const {goods = [], addToCart = Function.prototype} = props;

    return <div className="row">
        <div className="goods" >
            {

                goods.map(item => {
                    // <div key={item.mainId}>test</div>
                    let count = 0;
                    return <GoodsItem
                      key={item.mainId} {...item}
                      // addToCart={addToCart}
                    />
                  }
                )
            }
            </div>
        </div>
}

export default GoodsList;
