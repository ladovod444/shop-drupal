import React from 'react';
import Shop from './Shop';
import DrupalProjectStats from './DrupalProjectStats';
import NodeListOnly from './NodeListOnly';

function App() {

  const default_project = 'drupal'
  return <>
    <Shop />
    <DrupalProjectStats />
    <NodeListOnly />
  </>
}

export default App
