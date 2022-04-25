const express = require('express')
let route = express.Router()

// returns some basic information about the API
route.get('/', function (req, res) {
    const DividerLine = "_______________________________________\n";
    const DESC_INFO = "Client-side asked for the API information.\n";
    const API_INFO = {
        version: process.env.VERSION,
        port: process.env.PORT
    }
    console.info(DESC_INFO);
    console.info(DividerLine);
    console.info(API_INFO);
    console.info(DividerLine);
    res.send(API_INFO);
});

module.exports = route