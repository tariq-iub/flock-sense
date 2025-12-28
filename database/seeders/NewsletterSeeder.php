<?php

namespace Database\Seeders;

use App\Models\Newsletter;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NewsletterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newsletters = [
            [
                'subject' => 'Monthly FlockSense Update - January 2025',
                'preview_text' => 'Discover the latest features, tips for better flock management, and upcoming enhancements.',
                'content_html' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly FlockSense Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2c3e50;">Monthly FlockSense Update - January 2025</h1>
        <p>Dear FlockSense User,</p>
        <p>We hope this email finds you well! Here\'s what\'s new in FlockSense this month.</p>
        <h2 style="color: #27ae60;">New Features</h2>
        <ul>
            <li>Enhanced dashboard analytics</li>
            <li>Real-time IoT device monitoring</li>
            <li>Improved reporting tools</li>
        </ul>
        <p>Thank you for being a valued member of the FlockSense community!</p>
        <p>Best regards,<br>The FlockSense Team</p>
    </div>
</body>
</html>',
                'content_text' => 'Monthly FlockSense Update - January 2025

Dear FlockSense User,

We hope this email finds you well! Here\'s what\'s new in FlockSense this month.

New Features
- Enhanced dashboard analytics
- Real-time IoT device monitoring
- Improved reporting tools

Thank you for being a valued member of the FlockSense community!

Best regards,
The FlockSense Team',
                'status' => 'draft',
                'send_at' => null,
                'target_count' => 0,
                'sent_count' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'subject' => 'Flock Health Management Best Practices',
                'preview_text' => 'Learn essential tips for maintaining optimal flock health and preventing diseases.',
                'content_html' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Flock Health Management</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2c3e50;">Flock Health Management Best Practices</h1>
        <p>Hello,</p>
        <p>Maintaining optimal flock health is crucial for your poultry farming success. Here are some key practices:</p>
        <h2 style="color: #27ae60;">Key Tips</h2>
        <ul>
            <li>Monitor feed and water quality daily</li>
            <li>Maintain proper ventilation</li>
            <li>Regular health inspections</li>
            <li>Vaccination schedules</li>
        </ul>
        <p>Implement these practices for healthier, more productive flocks.</p>
        <p>Regards,<br>FlockSense Team</p>
    </div>
</body>
</html>',
                'content_text' => 'Flock Health Management Best Practices

Hello,

Maintaining optimal flock health is crucial for your poultry farming success. Here are some key practices:

Key Tips
- Monitor feed and water quality daily
- Maintain proper ventilation
- Regular health inspections
- Vaccination schedules

Implement these practices for healthier, more productive flocks.

Regards,
FlockSense Team',
                'status' => 'draft',
                'send_at' => null,
                'target_count' => 0,
                'sent_count' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'subject' => 'New IoT Sensors Available',
                'preview_text' => 'Introducing our latest temperature and humidity sensors for smarter farming.',
                'content_html' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New IoT Sensors Available</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2c3e50;">New IoT Sensors Available</h1>
        <p>Dear Farmer,</p>
        <p>We are excited to announce our new line of IoT sensors designed for modern poultry farming!</p>
        <h2 style="color: #27ae60;">Featured Products</h2>
        <ul>
            <li>Advanced temperature sensors</li>
            <li>Humidity monitors</li>
            <li>Air quality detectors</li>
            <li>Water level sensors</li>
        </ul>
        <p>Upgrade your farm with our cutting-edge technology today!</p>
        <p>Best,<br>FlockSense Team</p>
    </div>
</body>
</html>',
                'content_text' => 'New IoT Sensors Available

Dear Farmer,

We are excited to announce our new line of IoT sensors designed for modern poultry farming!

Featured Products
- Advanced temperature sensors
- Humidity monitors
- Air quality detectors
- Water level sensors

Upgrade your farm with our cutting-edge technology today!

Best,
FlockSense Team',
                'status' => 'draft',
                'send_at' => null,
                'target_count' => 0,
                'sent_count' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'subject' => 'Automated Feeding Systems Guide',
                'preview_text' => 'How automated feeding can improve efficiency and reduce waste in your poultry farm.',
                'content_html' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Automated Feeding Systems Guide</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2c3e50;">Automated Feeding Systems Guide</h1>
        <p>Hello Farmers,</p>
        <p>Automated feeding systems can significantly improve your poultry farming efficiency.</p>
        <h2 style="color: #27ae60;">Benefits</h2>
        <ul>
            <li>Reduce feed waste by up to 30%</li>
            <li>Ensure consistent feeding schedules</li>
            <li>Monitor consumption patterns</li>
            <li>Save labor costs</li>
        </ul>
        <p>Learn more about implementing automated systems in your farm today.</p>
        <p>Warm regards,<br>FlockSense Team</p>
    </div>
</body>
</html>',
                'content_text' => 'Automated Feeding Systems Guide

Hello Farmers,

Automated feeding systems can significantly improve your poultry farming efficiency.

Benefits
- Reduce feed waste by up to 30%
- Ensure consistent feeding schedules
- Monitor consumption patterns
- Save labor costs

Learn more about implementing automated systems in your farm today.

Warm regards,
FlockSense Team',
                'status' => 'draft',
                'send_at' => null,
                'target_count' => 0,
                'sent_count' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'subject' => 'Winter Care for Your Flock',
                'preview_text' => 'Essential tips to protect your poultry during cold weather months.',
                'content_html' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Winter Care for Your Flock</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2c3e50;">Winter Care for Your Flock</h1>
        <p>Dear Farmers,</p>
        <p>As winter approaches, it\'s important to prepare your flock for the colder months.</p>
        <h2 style="color: #27ae60;">Winter Preparation Checklist</h2>
        <ul>
            <li>Check insulation in sheds</li>
            <li>Ensure proper heating systems</li>
            <li>Monitor temperature closely</li>
            <li>Adjust feeding schedules</li>
            <li>Provide adequate bedding</li>
        </ul>
        <p>Follow these guidelines to keep your flock healthy and productive throughout winter!</p>
        <p>Stay warm,<br>FlockSense Team</p>
    </div>
</body>
</html>',
                'content_text' => 'Winter Care for Your Flock

Dear Farmers,

As winter approaches, it\'s important to prepare your flock for the colder months.

Winter Preparation Checklist
- Check insulation in sheds
- Ensure proper heating systems
- Monitor temperature closely
- Adjust feeding schedules
- Provide adequate bedding

Follow these guidelines to keep your flock healthy and productive throughout winter!

Stay warm,
FlockSense Team',
                'status' => 'draft',
                'send_at' => null,
                'target_count' => 0,
                'sent_count' => 0,
                'created_by' => 1,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
        ];

        foreach ($newsletters as $newsletter) {
            Newsletter::firstOrCreate([
                'subject' => $newsletter['subject'],
            ], $newsletter);
        }
    }
}
