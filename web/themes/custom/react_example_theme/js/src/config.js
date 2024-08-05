//import process from 'process'

//require('dotenv').config()

//require('dotenv').config({ path: require('find-config')('.env') })

//console.log(require("dotenv").config())

// const process = require('process');
//  import dotenv from "dotenv"
// dotenv.config();
// // import * as path from 'path';
// //dotenv.config({ path: path.join(__dirname, '..', '.env') });
//
// //////dotenv.config({ path: __dirname });
//
// console.log(__dirname);
// console.log(__filename);

//dotenv.config({ path: './env' });
//console.log(path.join(__dirname, '..', '.env'));

// const path = require('path')
// require('dotenv').config({path: path.relative(process.cwd(), path.join(__dirname,'.env'))});


//console.log(process);

const API_KEY = process.env.REACT_APP_API_KEY
const API_URL = process.env.REACT_APP_API_URL;

const GRANT_TYPE = process.env.REACT_APP_GRANT_TYPE
const CLIENT_ID = process.env.REACT_APP_CLIENT_ID
const CLIENT_SECRET = process.env.REACT_APP_CLIENT_SECRET
const SCOPE = process.env.REACT_APP_SCOPE
const USERNAME = process.env.REACT_APP_USERNAME
const PASSWORD = process.env.REACT_APP_PASSWORD
const SHOP_URL = process.env.REACT_APP_SHOP_URL
const OAUTH_TOKEN_URL = process.env.REACT_APP_SHOP_OAUTH_TOKEN_URL
const API_PRODUCTS = process.env.REACT_APP_SHOP_API_PRODUCTS
const API_PRODUCT = process.env.REACT_APP_SHOP_API_PRODUCT
const API_USER = process.env.REACT_APP_SHOP_API_USER
const API_CREATE_ORDER = process.env.REACT_APP_SHOP_API_CREATE_ORDER
const API_USER_REGISTER = process.env.REACT_APP_SHOP_API_USER_REGISTER
const API_SEND_CONTACTS_FORM = process.env.REACT_APP_SHOP_API_CONTACTS
const APP_TEST = process.env.REACT_APP_TEST

const projects = [
  {key: 'drupal', value: 'Drupal'},
  {key: 'marquee', value: 'Marquee'},
  {key: 'token', value: 'Token'},
  {key: 'pathauto', value: 'Pathauto'},
];

export  {
   API_KEY,
   API_URL,
   GRANT_TYPE,
   CLIENT_ID,
   CLIENT_SECRET,
   SCOPE,
   USERNAME,
   PASSWORD,
   SHOP_URL,
   OAUTH_TOKEN_URL,
   API_PRODUCTS,
   API_PRODUCT,
   API_USER,
   API_CREATE_ORDER,
   API_USER_REGISTER,
   API_SEND_CONTACTS_FORM,
  APP_TEST,
  projects
}
