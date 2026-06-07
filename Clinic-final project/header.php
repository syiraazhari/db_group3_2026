<!DOCTYPE html>
<html>
<head>
    <title>Clinic System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial;
            background: #f0f0f0;
        }
        .navbar {
            background: #2c3e50;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .navbar a:hover {
            background: #3498db;
        }
        .logout-btn {
            background: #e74c3c;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #3498db;
            color: white;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-primary {
            background: #3498db;
            color: white;
        }
        .btn-success {
            background: #27ae60;
            color: white;
        }
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .status-pending {
            background: #ffeaa7;
            color: #d35400;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .status-completed {
            background: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .status-dispensed {
            background: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 20px;
        }
        .dashboard-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #ecf0f1;
            padding: 20px;
            border-radius: 10px;
            flex: 1;
            text-align: center;
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #3498db;
        }
        .search-box {
            margin-bottom: 20px;
            padding: 10px;
            width: 300px;
        }
        .welcome {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>