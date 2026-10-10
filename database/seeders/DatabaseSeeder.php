<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with categories, books, copies, users, reservations, and loans.
     */
    public function run(): void
    {
        // 1. Seed System Users (1 Librarian, 3 Primary Students + 2 Additional Students)
        $librarian = User::create([
            'name' => 'Head Librarian',
            'email' => 'librarian@library.test',
            'password' => Hash::make('password'),
            'role' => 'librarian',
            'student_id' => null,
        ]);

        $student1 = User::create([
            'name' => 'Alice Smith',
            'email' => 'student1@library.test',
            'password' => Hash::make('password'),
            'role' => 'student',
            'student_id' => 'STU001',
        ]);

        $student2 = User::create([
            'name' => 'Bob Johnson',
            'email' => 'student2@library.test',
            'password' => Hash::make('password'),
            'role' => 'student',
            'student_id' => 'STU002',
        ]);

        $student3 = User::create([
            'name' => 'Charlie Brown',
            'email' => 'student3@library.test',
            'password' => Hash::make('password'),
            'role' => 'student',
            'student_id' => 'STU003',
        ]);

        $student4 = User::create([
            'name' => 'Diana Prince',
            'email' => 'student4@library.test',
            'password' => Hash::make('password'),
            'role' => 'student',
            'student_id' => 'STU004',
        ]);

        $student5 = User::create([
            'name' => 'Evan Wright',
            'email' => 'student5@library.test',
            'password' => Hash::make('password'),
            'role' => 'student',
            'student_id' => 'STU005',
        ]);

        // 2. Seed 6 Distinct Categories
        $categoriesData = [
            'Computer Science & Software Engineering',
            'Literature & Classic Fiction',
            'Business, Finance & Management',
            'Science, Physics & Technology',
            'History & World Philosophy',
            'Mathematics & Data Science',
        ];

        $categories = [];
        foreach ($categoriesData as $catName) {
            $categories[$catName] = Category::create(['name' => $catName]);
        }

        // 3. Seed 40 Realistic Books with Real Authors, ISBNs, and Descriptions
        $booksData = [
            // Category: Computer Science & Software Engineering
            [
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'author' => 'Robert C. Martin',
                'isbn' => '978-0132350884',
                'category' => 'Computer Science & Software Engineering',
                'publisher' => 'Prentice Hall',
                'published_year' => 2008,
                'description' => 'Even bad code can function. But if code isn’t clean, it can bring a development organization to its knees.',
            ],
            [
                'title' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
                'author' => 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides',
                'isbn' => '978-0201633610',
                'category' => 'Computer Science & Software Engineering',
                'publisher' => 'Addison-Wesley Professional',
                'published_year' => 1994,
                'description' => 'Captures a wealth of experience in designing object-oriented software as four top-notch designers present a catalog of simple and succinct solutions.',
            ],
            [
                'title' => 'The Pragmatic Programmer: Your Journey to Mastery',
                'author' => 'David Thomas, Andrew Hunt',
                'isbn' => '978-0135957059',
                'category' => 'Computer Science & Software Engineering',
                'publisher' => 'Addison-Wesley Professional',
                'published_year' => 2019,
                'description' => 'Illustrates the best approaches and major pitfalls of many different aspects of software development.',
            ],
            [
                'title' => 'Introduction to Algorithms',
                'author' => 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest, Clifford Stein',
                'isbn' => '978-0262046305',
                'category' => 'Computer Science & Software Engineering',
                'publisher' => 'MIT Press',
                'published_year' => 2022,
                'description' => 'A comprehensive update of the leading textbook on algorithms, covering a broad spectrum of algorithms in depth.',
            ],
            [
                'title' => 'Designing Data-Intensive Applications',
                'author' => 'Martin Kleppmann',
                'isbn' => '978-1449373320',
                'category' => 'Computer Science & Software Engineering',
                'publisher' => 'O\'Reilly Media',
                'published_year' => 2017,
                'description' => 'An indispensable guide for software architects and engineers exploring data storage, processing, and distributed systems.',
            ],
            [
                'title' => 'Structure and Interpretation of Computer Programs',
                'author' => 'Harold Abelson, Gerald Jay Sussman',
                'isbn' => '978-0262510875',
                'category' => 'Computer Science & Software Engineering',
                'publisher' => 'MIT Press',
                'published_year' => 1996,
                'description' => 'A classic computer science textbook that emphasizes programming methodology and fundamental computer architecture ideas.',
            ],
            [
                'title' => 'Refactoring: Improving the Design of Existing Code',
                'author' => 'Martin Fowler',
                'isbn' => '978-0134757599',
                'category' => 'Computer Science & Software Engineering',
                'publisher' => 'Addison-Wesley Professional',
                'published_year' => 2018,
                'description' => 'Fully updated guide explaining refactoring principles, code smells, and step-by-step code transformation techniques.',
            ],
            [
                'title' => 'Domain-Driven Design: Tackling Complexity in the Heart of Software',
                'author' => 'Eric Evans',
                'isbn' => '978-0321125217',
                'category' => 'Computer Science & Software Engineering',
                'publisher' => 'Addison-Wesley Professional',
                'published_year' => 2003,
                'description' => 'Presents a systematic approach to domain-driven design, incorporating design best practices and object modeling.',
            ],
            [
                'title' => 'Code Complete: A Practical Handbook of Software Construction',
                'author' => 'Steve McConnell',
                'isbn' => '978-0735619678',
                'category' => 'Computer Science & Software Engineering',
                'publisher' => 'Microsoft Press',
                'published_year' => 2004,
                'description' => 'A comprehensive reference guide covering software construction, debugging, testing, and code quality control.',
            ],
            [
                'title' => 'Head First Design Patterns',
                'author' => 'Eric Freeman, Elisabeth Robson',
                'isbn' => '978-1492078005',
                'category' => 'Computer Science & Software Engineering',
                'publisher' => 'O\'Reilly Media',
                'published_year' => 2020,
                'description' => 'A visually rich, highly engaging guide to object-oriented software design patterns.',
            ],

            // Category: Literature & Classic Fiction
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '978-0451524935',
                'category' => 'Literature & Classic Fiction',
                'publisher' => 'Signet Classic',
                'published_year' => 1949,
                'description' => 'A dystopian social science fiction novel and cautionary tale about totalitarianism, mass surveillance, and repressive regimentation.',
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '978-0061120084',
                'category' => 'Literature & Classic Fiction',
                'publisher' => 'Harper Perennial Modern Classics',
                'published_year' => 1960,
                'description' => 'A Pulitzer Prize-winning masterpiece exploring racial injustice and the destruction of innocence in the American South.',
            ],
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => '978-0743273565',
                'category' => 'Literature & Classic Fiction',
                'publisher' => 'Scribner',
                'published_year' => 1925,
                'description' => 'A portrait of the Jazz Age and American Dream through the life of mysterious millionaire Jay Gatsby.',
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'isbn' => '978-0141439518',
                'category' => 'Literature & Classic Fiction',
                'publisher' => 'Penguin Classics',
                'published_year' => 1813,
                'description' => 'A romantic novel of manners following the turbulent relationship between Elizabeth Bennet and Fitzwilliam Darcy.',
            ],
            [
                'title' => 'Crime and Punishment',
                'author' => 'Fyodor Dostoevsky',
                'isbn' => '978-0143107637',
                'category' => 'Literature & Classic Fiction',
                'publisher' => 'Penguin Classics',
                'published_year' => 1866,
                'description' => 'Focuses on the mental anguish and moral dilemmas of Rodion Raskolnikov, an impoverished ex-student in Saint Petersburg.',
            ],
            [
                'title' => 'Brave New World',
                'author' => 'Aldous Huxley',
                'isbn' => '978-0060850524',
                'category' => 'Literature & Classic Fiction',
                'publisher' => 'Harper Perennial',
                'published_year' => 1932,
                'description' => 'A classic dystopian novel set in a technologically advanced World State built around social engineering and consumption.',
            ],
            [
                'title' => 'The Catcher in the Rye',
                'author' => 'J.D. Salinger',
                'isbn' => '978-0316769488',
                'category' => 'Literature & Classic Fiction',
                'publisher' => 'Little, Brown and Company',
                'published_year' => 1951,
                'description' => 'Follows teenager Holden Caulfield as he navigates feelings of alienation and loss in New York City.',
            ],
            [
                'title' => 'One Hundred Years of Solitude',
                'author' => 'Gabriel García Márquez',
                'isbn' => '978-0060883287',
                'category' => 'Literature & Classic Fiction',
                'publisher' => 'Harper Perennial',
                'published_year' => 1967,
                'description' => 'The landmark magical realist novel depicting multi-generational history of the Buendía family in Macondo.',
            ],

            // Category: Business, Finance & Management
            [
                'title' => 'The Intelligent Investor',
                'author' => 'Benjamin Graham',
                'isbn' => '978-0060555665',
                'category' => 'Business, Finance & Management',
                'publisher' => 'Harper Business',
                'published_year' => 1949,
                'description' => 'The definitive book on value investing, teaching long-term strategies for stock market analysis and risk management.',
            ],
            [
                'title' => 'Principles: Life and Work',
                'author' => 'Ray Dalio',
                'isbn' => '978-1501124020',
                'category' => 'Business, Finance & Management',
                'publisher' => 'Simon & Schuster',
                'published_year' => 2017,
                'description' => 'Shares the unconventional principles created and refined by legendary investor Ray Dalio over forty years.',
            ],
            [
                'title' => 'Good to Great: Why Some Companies Make the Leap... and Others Don\'t',
                'author' => 'Jim Collins',
                'isbn' => '978-0066620992',
                'category' => 'Business, Finance & Management',
                'publisher' => 'Harper Business',
                'published_year' => 2001,
                'description' => 'Identifies the key factors that enable ordinary companies to become elite, long-term outperformers.',
            ],
            [
                'title' => 'Zero to One: Notes on Startups, or How to Build the Future',
                'author' => 'Peter Thiel, Blake Masters',
                'isbn' => '978-0804139298',
                'category' => 'Business, Finance & Management',
                'publisher' => 'Crown Business',
                'published_year' => 2014,
                'description' => 'Shows how to create innovative new technology breakthroughs rather than duplicating existing businesses.',
            ],
            [
                'title' => 'Thinking, Fast and Slow',
                'author' => 'Daniel Kahneman',
                'isbn' => '978-0374533557',
                'category' => 'Business, Finance & Management',
                'publisher' => 'Farrar, Straus and Giroux',
                'published_year' => 2011,
                'description' => 'Explains the two systems that drive the way we think: System 1 (fast, intuitive) and System 2 (slow, analytical).',
            ],
            [
                'title' => 'Atomic Habits: An Easy & Proven Way to Build Good Habits & Break Bad Ones',
                'author' => 'James Clear',
                'isbn' => '978-0735211292',
                'category' => 'Business, Finance & Management',
                'publisher' => 'Avery',
                'published_year' => 2018,
                'description' => 'A practical guide on how small daily behavioral changes lead to massive personal and professional success.',
            ],
            [
                'title' => 'The Lean Startup',
                'author' => 'Eric Ries',
                'isbn' => '978-0307887894',
                'category' => 'Business, Finance & Management',
                'publisher' => 'Crown Business',
                'published_year' => 2011,
                'description' => 'Presents continuous innovation and validated learning frameworks to build successful startup products.',
            ],
            [
                'title' => 'Rich Dad Poor Dad',
                'author' => 'Robert T. Kiyosaki',
                'isbn' => '978-1612680194',
                'category' => 'Business, Finance & Management',
                'publisher' => 'Plata Publishing',
                'published_year' => 1997,
                'description' => 'Explores financial literacy, asset accumulation, and financial independence through personal stories.',
            ],

            // Category: Science, Physics & Technology
            [
                'title' => 'A Brief History of Time',
                'author' => 'Stephen Hawking',
                'isbn' => '978-0553380163',
                'category' => 'Science, Physics & Technology',
                'publisher' => 'Bantam',
                'published_year' => 1988,
                'description' => 'Explores fundamental questions about cosmology, black holes, the Big Bang, and the nature of time.',
            ],
            [
                'title' => 'The Selfish Gene',
                'author' => 'Richard Dawkins',
                'isbn' => '978-0199291151',
                'category' => 'Science, Physics & Technology',
                'publisher' => 'Oxford University Press',
                'published_year' => 1976,
                'description' => 'A gene-centric view of evolution that altered how biologists and the public view natural selection.',
            ],
            [
                'title' => 'Cosmos',
                'author' => 'Carl Sagan',
                'isbn' => '978-0345331359',
                'category' => 'Science, Physics & Technology',
                'publisher' => 'Ballantine Books',
                'published_year' => 1980,
                'description' => 'Traces 15 billion years of cosmic evolution and scientific exploration in poetic detail.',
            ],
            [
                'title' => 'Sapiens: A Brief History of Humankind',
                'author' => 'Yuval Noah Harari',
                'isbn' => '978-0062316097',
                'category' => 'Science, Physics & Technology',
                'publisher' => 'Harper',
                'published_year' => 2014,
                'description' => 'Surveys the history of humanity from the Cognitive Revolution to contemporary biotechnology.',
            ],
            [
                'title' => 'The Gene: An Intimate History',
                'author' => 'Siddhartha Mukherjee',
                'isbn' => '978-1476733500',
                'category' => 'Science, Physics & Technology',
                'publisher' => 'Scribner',
                'published_year' => 2016,
                'description' => 'A comprehensive history of genetics, human heredity, and the future of gene editing.',
            ],
            [
                'title' => 'Astrophysics for People in a Hurry',
                'author' => 'Neil deGrasse Tyson',
                'isbn' => '978-0393609394',
                'category' => 'Science, Physics & Technology',
                'publisher' => 'W. W. Norton & Company',
                'published_year' => 2017,
                'description' => 'A quick, engaging overview of black holes, quantum mechanics, and cosmic expansion.',
            ],

            // Category: History & World Philosophy
            [
                'title' => 'Guns, Germs, and Steel: The Fates of Human Societies',
                'author' => 'Jared Diamond',
                'isbn' => '978-0393317558',
                'category' => 'History & World Philosophy',
                'publisher' => 'W. W. Norton & Company',
                'published_year' => 1997,
                'description' => 'Examines environmental and geographical factors that shaped the rise of human civilizations.',
            ],
            [
                'title' => 'Meditations',
                'author' => 'Marcus Aurelius',
                'isbn' => '978-0812968255',
                'category' => 'History & World Philosophy',
                'publisher' => 'Modern Library',
                'published_year' => 180,
                'description' => 'Personal writings by the Roman Emperor Marcus Aurelius detailing Stoic philosophy and self-discipline.',
            ],
            [
                'title' => 'The Lessons of History',
                'author' => 'Will Durant, Ariel Durant',
                'isbn' => '978-1439149959',
                'category' => 'History & World Philosophy',
                'publisher' => 'Simon & Schuster',
                'published_year' => 1968,
                'description' => 'A concise survey of human history, culture, economics, and human nature.',
            ],
            [
                'title' => 'The Silk Roads: A New History of the World',
                'author' => 'Peter Frankopan',
                'isbn' => '978-1101912379',
                'category' => 'History & World Philosophy',
                'publisher' => 'Vintage',
                'published_year' => 2015,
                'description' => 'Re-evaluates world history through the lens of trade networks connecting Asia, Europe, and Africa.',
            ],

            // Category: Mathematics & Data Science
            [
                'title' => 'The Art of Computer Programming, Volume 1: Fundamental Algorithms',
                'author' => 'Donald E. Knuth',
                'isbn' => '978-0201896831',
                'category' => 'Mathematics & Data Science',
                'publisher' => 'Addison-Wesley Professional',
                'published_year' => 1997,
                'description' => 'The foundational text for computer programmers and mathematicians studying algorithms.',
            ],
            [
                'title' => 'Linear Algebra and Its Applications',
                'author' => 'Gilbert Strang',
                'isbn' => '978-0030105678',
                'category' => 'Mathematics & Data Science',
                'publisher' => 'Cengage Learning',
                'published_year' => 2005,
                'description' => 'Renowned textbook presenting matrix theory, vector spaces, and linear transformation applications.',
            ],
            [
                'title' => 'Calculus: Early Transcendentals',
                'author' => 'James Stewart',
                'isbn' => '978-1285741550',
                'category' => 'Mathematics & Data Science',
                'publisher' => 'Cengage Learning',
                'published_year' => 2015,
                'description' => 'Comprehensive calculus textbook covering limits, derivatives, integration, and multivariable calculus.',
            ],
            [
                'title' => 'Probability Theory: The Logic of Science',
                'author' => 'E.T. Jaynes',
                'isbn' => '978-0521592710',
                'category' => 'Mathematics & Data Science',
                'publisher' => 'Cambridge University Press',
                'published_year' => 2003,
                'description' => 'Develops probability theory as an extension of formal logic to uncertain inference.',
            ],
        ];

        $booksList = [];
        $bookIdCounter = 1000;

        foreach ($booksData as $bData) {
            $bookIdCounter++;
            $book = Book::create([
                'title' => $bData['title'],
                'author' => $bData['author'],
                'isbn' => $bData['isbn'],
                'category_id' => $categories[$bData['category']]->id,
                'publisher' => $bData['publisher'],
                'published_year' => $bData['published_year'],
                'description' => $bData['description'],
                'cover_image' => null,
            ]);

            // Create between 1 and 5 copies for each book
            $copiesCount = rand(1, 5);
            for ($c = 1; $c <= $copiesCount; $c++) {
                BookCopy::create([
                    'book_id' => $book->id,
                    'copy_code' => sprintf('BK%04d-C%d', $bookIdCounter, $c),
                    'status' => 'available',
                ]);
            }

            $booksList[] = $book;
        }

        // 4. Seed Sample Reservations (Pending, Collected, Cancelled, Expired)
        // Reservation 1: Alice has a pending reservation for Clean Code Copy #1
        $cleanCode = $booksList[0]; // Clean Code
        $cleanCodeCopy1 = $cleanCode->copies()->first();
        if ($cleanCodeCopy1) {
            $cleanCodeCopy1->update(['status' => 'reserved']);
            Reservation::create([
                'user_id' => $student1->id,
                'book_copy_id' => $cleanCodeCopy1->id,
                'status' => 'pending',
                'reserved_at' => now()->subHours(5),
                'expires_at' => now()->addHours(43),
            ]);
        }

        // Reservation 2: Bob has a pending reservation for 1984 Copy #1
        $book1984 = $booksList[10]; // 1984
        $book1984Copy1 = $book1984->copies()->first();
        if ($book1984Copy1) {
            $book1984Copy1->update(['status' => 'reserved']);
            Reservation::create([
                'user_id' => $student2->id,
                'book_copy_id' => $book1984Copy1->id,
                'status' => 'pending',
                'reserved_at' => now()->subHours(12),
                'expires_at' => now()->addHours(36),
            ]);
        }

        // Reservation 3: Expired reservation for Charlie
        $designPatterns = $booksList[1]; // Design Patterns
        $dpCopy1 = $designPatterns->copies()->first();
        if ($dpCopy1) {
            Reservation::create([
                'user_id' => $student3->id,
                'book_copy_id' => $dpCopy1->id,
                'status' => 'expired',
                'reserved_at' => now()->subDays(4),
                'expires_at' => now()->subDays(2),
            ]);
        }

        // Reservation 4: Cancelled reservation for Diana
        $pragmaticProg = $booksList[2]; // Pragmatic Programmer
        $ppCopy1 = $pragmaticProg->copies()->first();
        if ($ppCopy1) {
            Reservation::create([
                'user_id' => $student4->id,
                'book_copy_id' => $ppCopy1->id,
                'status' => 'cancelled',
                'reserved_at' => now()->subDays(3),
                'expires_at' => now()->subDays(1),
            ]);
        }

        // 5. Seed Sample Loans (Active, Returned, and Overdue with Fines)

        // Loan 1: Active Loan (within due date) - Alice borrowed "Designing Data-Intensive Applications"
        $ddia = $booksList[4];
        $ddiaCopy1 = $ddia->copies()->first();
        if ($ddiaCopy1) {
            $ddiaCopy1->update(['status' => 'borrowed']);
            Loan::create([
                'user_id' => $student1->id,
                'book_copy_id' => $ddiaCopy1->id,
                'reservation_id' => null,
                'issued_at' => now()->subDays(5),
                'due_date' => now()->addDays(9),
                'returned_at' => null,
                'fine_amount' => 0.00,
                'fine_paid' => false,
            ]);
        }

        // Loan 2: OVERDUE Loan - Bob borrowed "The Intelligent Investor" 20 days ago (due 6 days ago)
        $intelligentInvestor = $booksList[18];
        $iiCopy1 = $intelligentInvestor->copies()->first();
        if ($iiCopy1) {
            $iiCopy1->update(['status' => 'borrowed']);
            Loan::create([
                'user_id' => $student2->id,
                'book_copy_id' => $iiCopy1->id,
                'reservation_id' => null,
                'issued_at' => now()->subDays(20),
                'due_date' => now()->subDays(6), // 6 days overdue
                'returned_at' => null,
                'fine_amount' => 15.00, // 6 days * $2.50
                'fine_paid' => false,
            ]);
        }

        // Loan 3: Returned Loan with Paid Fine - Charlie borrowed "Atomic Habits"
        $atomicHabits = $booksList[23];
        $ahCopy1 = $atomicHabits->copies()->first();
        if ($ahCopy1) {
            // Collected from reservation
            $res = Reservation::create([
                'user_id' => $student3->id,
                'book_copy_id' => $ahCopy1->id,
                'status' => 'collected',
                'reserved_at' => now()->subDays(25),
                'expires_at' => now()->subDays(23),
            ]);

            Loan::create([
                'user_id' => $student3->id,
                'book_copy_id' => $ahCopy1->id,
                'reservation_id' => $res->id,
                'issued_at' => now()->subDays(23),
                'due_date' => now()->subDays(9),
                'returned_at' => now()->subDays(5), // returned 4 days late
                'fine_amount' => 10.00, // 4 days * $2.50
                'fine_paid' => true,
            ]);
        }

        // Loan 4: OVERDUE Loan - Evan borrowed "Sapiens" 18 days ago (due 4 days ago)
        $sapiens = $booksList[29];
        $sapiensCopy1 = $sapiens->copies()->first();
        if ($sapiensCopy1) {
            $sapiensCopy1->update(['status' => 'borrowed']);
            Loan::create([
                'user_id' => $student5->id,
                'book_copy_id' => $sapiensCopy1->id,
                'reservation_id' => null,
                'issued_at' => now()->subDays(18),
                'due_date' => now()->subDays(4), // 4 days overdue
                'returned_at' => null,
                'fine_amount' => 10.00, // 4 days * $2.50
                'fine_paid' => false,
            ]);
        }
    }
}
