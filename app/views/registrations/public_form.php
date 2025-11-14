<?php
/**
 * Public Event Registration Form
 * Accessible without authentication
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="date"]:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-row.full {
            grid-template-columns: 1fr;
        }
        .required {
            color: #e74c3c;
        }
        .error {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 4px;
        }
        .form-group.error input,
        .form-group.error select {
            border-color: #e74c3c;
            background-color: #fadbd8;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-submit {
            background: #667eea;
            color: white;
        }
        .btn-submit:hover {
            background: #5568d3;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .btn-reset {
            background: #ecf0f1;
            color: #333;
        }
        .btn-reset:hover {
            background: #d5dbdb;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-error {
            background-color: #fadbd8;
            color: #a93226;
            border: 1px solid #e74c3c;
        }
        .alert-success {
            background-color: #d5f4e6;
            color: #0b5345;
            border: 1px solid #27ae60;
        }
        .info-box {
            background: #ecf0f1;
            border-left: 4px solid #667eea;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 2px;
            font-size: 13px;
            color: #555;
        }
        @media (max-width: 640px) {
            .container {
                padding: 20px;
            }
            h1 {
                font-size: 24px;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Event Registration</h1>
        <p class="subtitle"><?php echo htmlspecialchars($eventName ?? 'Register for Event'); ?></p>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <div class="info-box">
            Fill in your details below to register for this event. All fields marked with <span class="required">*</span> are required.
        </div>
        
        <form method="POST" action="/registrations/submit">
            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($eventId ?? ''); ?>">
            <?php if (isset($eventCategoryId)): ?>
                <input type="hidden" name="event_category_id" value="<?php echo htmlspecialchars($eventCategoryId); ?>">
            <?php endif; ?>
            
            <!-- Personal Information -->
            <div class="form-group <?php echo isset($errors['athlete_name']) ? 'error' : ''; ?>">
                <label for="athlete_name">Full Name <span class="required">*</span></label>
                <input type="text" id="athlete_name" name="athlete_name" value="<?php echo htmlspecialchars($data['athlete_name'] ?? ''); ?>" required>
                <?php if (isset($errors['athlete_name'])): ?>
                    <div class="error"><?php echo htmlspecialchars($errors['athlete_name']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-row">
                <div class="form-group <?php echo isset($errors['athlete_dob']) ? 'error' : ''; ?>">
                    <label for="athlete_dob">Date of Birth <span class="required">*</span></label>
                    <input type="date" id="athlete_dob" name="athlete_dob" value="<?php echo htmlspecialchars($data['athlete_dob'] ?? ''); ?>" required>
                    <?php if (isset($errors['athlete_dob'])): ?>
                        <div class="error"><?php echo htmlspecialchars($errors['athlete_dob']); ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male" <?php echo ($data['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($data['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($data['gender'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="form-row">
                <div class="form-group <?php echo isset($errors['athlete_email']) ? 'error' : ''; ?>">
                    <label for="athlete_email">Email Address <span class="required">*</span></label>
                    <input type="email" id="athlete_email" name="athlete_email" value="<?php echo htmlspecialchars($data['athlete_email'] ?? ''); ?>" required>
                    <?php if (isset($errors['athlete_email'])): ?>
                        <div class="error"><?php echo htmlspecialchars($errors['athlete_email']); ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group <?php echo isset($errors['athlete_phone']) ? 'error' : ''; ?>">
                    <label for="athlete_phone">Phone Number <span class="required">*</span></label>
                    <input type="tel" id="athlete_phone" name="athlete_phone" value="<?php echo htmlspecialchars($data['athlete_phone'] ?? ''); ?>" required>
                    <?php if (isset($errors['athlete_phone'])): ?>
                        <div class="error"><?php echo htmlspecialchars($errors['athlete_phone']); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Organization Information -->
            <?php if (!empty($clubs)): ?>
                <div class="form-group">
                    <label for="club_id">Club/Organization <span class="required">*</span></label>
                    <select id="club_id" name="club_id" required>
                        <option value="">Select Club/Organization</option>
                        <?php foreach ($clubs as $club): ?>
                            <option value="<?php echo htmlspecialchars($club['id']); ?>" 
                                    <?php echo ($data['club_id'] ?? '') == $club['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($club['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($regions)): ?>
                <div class="form-group">
                    <label for="region_id">Region <span class="required">*</span></label>
                    <select id="region_id" name="region_id" required>
                        <option value="">Select Region</option>
                        <?php foreach ($regions as $region): ?>
                            <option value="<?php echo htmlspecialchars($region['id']); ?>" 
                                    <?php echo ($data['region_id'] ?? '') == $region['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($region['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            
            <!-- Bib Number (Optional) -->
            <div class="form-group">
                <label for="bib_number">Bib Number (Optional)</label>
                <input type="text" id="bib_number" name="bib_number" value="<?php echo htmlspecialchars($data['bib_number'] ?? ''); ?>" placeholder="e.g., 101">
            </div>
            
            <!-- Notes -->
            <div class="form-group">
                <label for="notes">Additional Notes (Optional)</label>
                <textarea id="notes" name="notes" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; font-size: 14px;"><?php echo htmlspecialchars($data['notes'] ?? ''); ?></textarea>
            </div>
            
            <!-- Buttons -->
            <div class="button-group">
                <button type="submit" class="btn-submit">Register</button>
                <button type="reset" class="btn-reset">Clear Form</button>
            </div>
        </form>
    </div>
</body>
</html>
