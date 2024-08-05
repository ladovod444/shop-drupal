import React from 'react';
import ReactDOM from 'react-dom/client';
import DrupalProjectStats from './components/DrupalProjectStats';
import Shop from './components/Shop';
import App from './components/App';

// # Example 1: Simple "Hello, World" code

const root = ReactDOM.createRoot(document.getElementById('react-app'));

root.render(
  <>
    <h1>Products</h1>
    <App />
    {/*<DrupalProjectStats />*/}
  </>
);

// ReactDOM.render(
//   <h1>Hello there - world!</h1>,
//   document.getElementById('react-app')
// );
