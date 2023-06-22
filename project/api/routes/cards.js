const express = require('express');
const router = express.Router();
const cards = require('../services/cards');

/* GET programming languages. */
router.get('/', async function(req, res, next) {
  try {
    res.json(await cards.getMultiple(req.query.page));
  } catch (err) {
    console.error(`Error while getting cards `, err.message);
    next(err);
  }
});

module.exports = router;