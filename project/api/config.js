const config = {
    db: {
      /* don't expose password or any sensitive info, done only for demo */
      host: "localhost",
      port: 9906,
      user: "root",
      password: "MYSQL_ROOT_PASSWORD",
      database: "phplogin",
    },
    listPerPage: 10,
  };
  module.exports = config;