<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title'           => 'Web Engineering Conference 2026',
                'description'     => 'Annual conference covering the latest trends in web engineering, microservices, and cloud architecture. Join top engineers and researchers from across Syria.',
                'location'        => 'Damascus International Conference Center',
                'event_date'      => now()->addDays(30),
                'price'           => 25.00,
                'total_seats'     => 200,
                'available_seats' => 200,
                'status'          => 'active',
                'created_by'      => 1,
            ],
            [
                'title'           => 'Laravel & PHP Workshop',
                'description'     => 'Hands-on workshop on building scalable web applications using Laravel framework. Perfect for intermediate developers looking to level up.',
                'location'        => 'Tech Hub — Aleppo',
                'event_date'      => now()->addDays(15),
                'price'           => 10.00,
                'total_seats'     => 50,
                'available_seats' => 50,
                'status'          => 'active',
                'created_by'      => 1,
            ],
            [
                'title'           => 'Database Design Masterclass',
                'description'     => 'Deep dive into relational database design, ACID transactions, and concurrency control strategies used in real-world production systems.',
                'location'        => 'University of Damascus — Faculty of Engineering',
                'event_date'      => now()->addDays(20),
                'price'           => 15.00,
                'total_seats'     => 80,
                'available_seats' => 80,
                'status'          => 'active',
                'created_by'      => 1,
            ],
            [
                'title'           => 'API Design Best Practices',
                'description'     => 'Learn RESTful API design, authentication strategies (OAuth2, JWT, Sanctum), and performance optimization for high-traffic applications.',
                'location'        => 'Online — Live Zoom Session',
                'event_date'      => now()->addDays(10),
                'price'           => 0.00,
                'total_seats'     => 500,
                'available_seats' => 500,
                'status'          => 'active',
                'created_by'      => 1,
            ],
            [
                'title'           => 'Cybersecurity Fundamentals',
                'description'     => 'Essential security concepts: SQL injection, XSS prevention, password hashing, secure coding patterns, and OWASP top 10 defense strategies.',
                'location'        => 'Syrian Cybersecurity Institute — Damascus',
                'event_date'      => now()->addDays(45),
                'price'           => 30.00,
                'total_seats'     => 60,
                'available_seats' => 60,
                'status'          => 'active',
                'created_by'      => 1,
            ],
            [
                'title'           => 'Cloud & DevOps Summit',
                'description'     => 'Explore cloud deployment strategies, CI/CD pipelines, containerization with Docker & Kubernetes, and infrastructure as code.',
                'location'        => 'International Expo Center — Damascus',
                'event_date'      => now()->addDays(60),
                'price'           => 20.00,
                'total_seats'     => 150,
                'available_seats' => 150,
                'status'          => 'active',
                'created_by'      => 1,
            ],
            [
                'title'           => 'React & Modern Frontend',
                'description'     => 'Comprehensive training on React 18, state management with Zustand & Redux, TypeScript, and building production-ready frontends.',
                'location'        => 'Innovation Center — Aleppo',
                'event_date'      => now()->addDays(25),
                'price'           => 12.00,
                'total_seats'     => 40,
                'available_seats' => 10,
                'status'          => 'active',
                'created_by'      => 1,
            ],
            [
                'title'           => 'AI & Machine Learning Bootcamp',
                'description'     => 'Intensive 3-day bootcamp covering machine learning fundamentals, neural networks, and practical AI applications using Python & PyTorch.',
                'location'        => 'Tishreen University — Lattakia',
                'event_date'      => now()->addDays(90),
                'price'           => 75.00,
                'total_seats'     => 30,
                'available_seats' => 30,
                'status'          => 'active',
                'created_by'      => 1,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
