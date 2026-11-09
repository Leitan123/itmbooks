import mysql from 'mysql2/promise';

// Prevent Windows IPv6 lookup delay with 'localhost' -> use 127.0.0.1 directly
const dbHost = !process.env.DB_HOST || process.env.DB_HOST === 'localhost'
  ? '127.0.0.1'
  : process.env.DB_HOST;

export const db = mysql.createPool({
  host: dbHost,
  port: process.env.DB_PORT ? parseInt(process.env.DB_PORT) : 3306,
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD ?? '',
  database: process.env.DB_NAME || 'itmbooks_db',
  waitForConnections: true,
  connectionLimit: 20,
  queueLimit: 0,
  connectTimeout: 4000,
  enableKeepAlive: true,
  keepAliveInitialDelay: 0
});

