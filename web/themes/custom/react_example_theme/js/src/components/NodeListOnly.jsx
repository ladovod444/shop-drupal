import React, { useState, useEffect } from "react";
import { PASSWORD, USERNAME } from '../config';
import { encode } from "base-64";
import NodeItem from './NodeItem';
function NodeListOnly() {

  const [content, setContent] = useState([]);
  const [filter, setFilter] = useState(null);
  const [type, setType] = useState('page');

  const site_url = 'https://shop-site.ddev.site/';
  const api_url = 'jsonapi/node/';

  const node_types= [
    {id: 'page', 'label': 'Page'},
    {id: 'article', 'label': 'Article'}
  ]

  const typeSelect = (e) => {
    const type = e.target.value;
    setType(type)
  }

  const searchOnChange = (e) => {
    const word = e.target.value;

    setFilter(word.toLowerCase());
  }

  useEffect(() => {
    fetch(site_url + api_url + '/' + type, {
      method: 'GET',
      headers: new Headers({
        'Authorization': 'Basic ' + encode(USERNAME + ":" + PASSWORD),
        'Content-Type': 'application/json'
      }),
      // headers: {
      //   'Authorization': '7XApls2RNEO6xmqVpbehZzbJGLOhtIkwAlHm5nU8ay25rK8XrVmAD1cybGQHr0xA',
      // },
    })
      .then(response => response.json())
      .then(data => setContent(data.data))
      .catch(err => console.log('There was an error accessing the API', err));
  }, [type]);


  return <>
  {content.length ? <div className="content">
    <div className="node-type">
      {node_types.map(node_type => {
        const checked = node_type.id === type
        return <label htmlFor={node_type.id} key={node_type.id}>{node_type.label}
          <input
            id={node_type.id}
            name="types"
            type="radio"
            value={node_type.id}
            onChange={typeSelect}
            checked={checked}
          />
        </label>
      })}
    </div>
    <input type="texfield" name="search" onChange={searchOnChange}/>
    {content.filter((item) => {
      if (!filter) {
        return item;
      }

      if (filter
        && (item.attributes.title.toLowerCase().includes(filter)
        || item.attributes.body.value.toLowerCase().includes(filter))
      ) {
        return item;
      }
    }).map((item) => <NodeItem key={item.id} {...item.attributes}/>) }
    </div> : ''
  }
  </>

}

export default NodeListOnly
