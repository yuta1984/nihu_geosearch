<?php
const API_KEY = 'AIzaSyBMQo0TRpgvgoRxyfIFxCj8vef7o34rWdo';
const HOST = "db";
const PORT = "3306";
const DB_NAME = "locales";
const USER_NAME = "root";
const PASSWORD = "mmjmmj";
# CORS対応
const HTTP_ORIGIN = '*';
const HTTP_ACCESS_CONTROL_REQUEST_METHOD = 'GET';
const HTTP_ACCESS_CONTROL_REQUEST_HEADERS = 'Content-Type, Authorization, X-Requested-With';

function h($str) {
  return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}
