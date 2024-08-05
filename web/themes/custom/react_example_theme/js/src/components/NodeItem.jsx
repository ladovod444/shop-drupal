import React, { useState, useEffect } from "react";
function NodeItem({drupal_internal__nid, title}) {

  //const {drupal_internal__nid, title} = props;

  //console.log(props);

  return <div>
    <a href={`/node/${drupal_internal__nid}`}>{title}</a>
  </div>

}

export default NodeItem
