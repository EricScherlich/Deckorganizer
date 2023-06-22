async function create(deck){
    const result = await db.query(
      `INSERT INTO decks 
      (owner) 
      VALUES 
      (${owner})`
    );
  
    let message = 'Error in creating a deck';
  
    if (result.affectedRows) {
      message = 'Deck created successfully';
    }
  
    return {message};
  }

  module.exports = {create};