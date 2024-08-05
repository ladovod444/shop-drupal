/**
 * @file
 * shop behaviors.
 */
(function (Drupal) {

  'use strict';

  Drupal.behaviors.shop = {
    attach (context, settings) {

      console.log('It works!');

    }
  };

} (Drupal));

function app() {
  return {
    init: function() {
      document.querySelector('.site-branding__name').addEventListener('click',() =>{
        alert('ddd');
      } )

      this.outputTest();
    },
    outputTest: function (message = 'my mess') {
      console.log(this);
      console.log(message);
    },

    init2: () => {
      //console.log(this);
    }
  }


  //document.querySelector('.site-branding__name').textContent = 'new text cont1';
  //init: loader()
  //return this;


}

// document.readyState= () => {
//   console.log(document.readyState);
//   if (document.readyState === "interactive") {
//     app();
//   }
// }

document.addEventListener("readystatechange", (event) => {

  if (event.target.readyState === "interactive") {
    app().init();
    app().init2();

  } else if (event.target.readyState === "complete") {

    const arr = [5, 6];
    arr[1] = 123421
    console.log(arr);
    //initApp();
    // for (let a = 1; a < 10; a++) {
    //   console.log(a);
    // }
    //console.log('a=', a); // Для let ReferenceError: a is not defined

    var v = 2;
    {
      //let v = 3;
      v = 4;
      console.log(v); // 3
    }
    console.log(v); // 2


    // let arr2 = [11, 22, 33];
    // c
    // console.log(...arr2);

    const myObj = {
      'name': 'Dm',
      'lastname': 'Iv',
    }

    console.log(myObj);


    //[n, ln ] = ...myObj;

    const user = {...myObj, password:'tpass'};

    console.log(user);


  }
});
