<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number Addition Calculator</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }
        
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .result {
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 8px;
            text-align: center;
        }
        
        .result h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 10px;
        }
        
        .result-value {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
        }
        
        .calculation {
            color: #666;
            font-size: 16px;
            margin-top: 10px;
        }
        
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✨ Number Addition Calculator</h1>
        
        <form action="{{ route('add.calculate') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="number1">First Number:</label>
                <input 
                    type="number" 
                    id="number1" 
                    name="number1" 
                    step="any"
                    value="{{ old('number1', $number1 ?? '') }}" 
                    placeholder="Enter first number"
                    required
                >
                @error('number1')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="number2">Second Number:</label>
                <input 
                    type="number" 
                    id="number2" 
                    name="number2" 
                    step="any"
                    value="{{ old('number2', $number2 ?? '') }}" 
                    placeholder="Enter second number"
                    required
                >
                @error('number2')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit">Calculate Sum</button>
        </form>
        
        @if(isset($result))
            <div class="result">
                <h2>Result:</h2>
                <div class="result-value">{{ $result }}</div>
                <div class="calculation">{{ $number1 }} + {{ $number2 }} = {{ $result }}</div>
            </div>
        @endif
    </div>
</body>
</html>
