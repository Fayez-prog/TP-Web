<?php
declare(strict_types=1);

class Book
{
    private string $title;
    private string $author;
    private int $publicationYear;
    private ?string $isbn;
    
    public function __construct(
        string $title, 
        string $author, 
        int $publicationYear,
        ?string $isbn = null
    ) {
        $this->setTitle($title);
        $this->setAuthor($author);
        $this->setPublicationYear($publicationYear);
        $this->setIsbn($isbn);
    }
    
    public function displayDetails(): string
    {
        $html = '<div class="book">';
        $html .= '<h2>' . htmlspecialchars($this->title) . '</h2>';
        $html .= '<p><strong>Author:</strong> ' . htmlspecialchars($this->author) . '</p>';
        $html .= '<p><strong>Publication Year:</strong> ' . $this->publicationYear . '</p>';
        
        if ($this->isbn) {
            $html .= '<p><strong>ISBN:</strong> ' . htmlspecialchars($this->isbn) . '</p>';
        }
        
        $html .= '<p><strong>Age:</strong> ' . $this->calculateAge() . ' years</p>';
        
        if ($this->isOld()) {
            $html .= '<p class="old-book">This is an old book (published over 50 years ago).</p>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    public function isOld(int $threshold = 50): bool
    {
        return $this->calculateAge() > $threshold;
    }
    
    public function calculateAge(): int
    {
        return (int)date('Y') - $this->publicationYear;
    }
    
    // Getters
    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function getAuthor(): string
    {
        return $this->author;
    }
    
    public function getPublicationYear(): int
    {
        return $this->publicationYear;
    }
    
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }
    
    // Setters with validation
    public function setTitle(string $title): void
    {
        $title = trim($title);
        if (empty($title)) {
            throw new InvalidArgumentException('Title cannot be empty');
        }
        $this->title = $title;
    }
    
    public function setAuthor(string $author): void
    {
        $author = trim($author);
        if (empty($author)) {
            throw new InvalidArgumentException('Author cannot be empty');
        }
        $this->author = $author;
    }
    
    public function setPublicationYear(int $year): void
    {
        $currentYear = (int)date('Y');
        if ($year > $currentYear + 1) { // +1 pour les livres à paraître
            throw new InvalidArgumentException(
                sprintf('Publication year cannot be in the future (max %d)', $currentYear + 1)
            );
        }
        if ($year < 0) {
            throw new InvalidArgumentException('Publication year must be positive');
        }
        $this->publicationYear = $year;
    }
    
    public function setIsbn(?string $isbn): void
    {
        if ($isbn !== null) {
            $isbn = str_replace([' ', '-'], '', trim($isbn));
            if (!preg_match('/^(978|979)\d{10}$/', $isbn)) {
                throw new InvalidArgumentException('Invalid ISBN format');
            }
        }
        $this->isbn = $isbn;
    }
    
    public function __toString(): string
    {
        $info = sprintf(
            '"%s" by %s (%d)',
            $this->title,
            $this->author,
            $this->publicationYear
        );
        
        if ($this->isbn) {
            $info .= ' [ISBN: ' . $this->isbn . ']';
        }
        
        return $info;
    }
    
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'author' => $this->author,
            'publicationYear' => $this->publicationYear,
            'isbn' => $this->isbn,
            'age' => $this->calculateAge(),
            'isOld' => $this->isOld(),
        ];
    }
}

// Helper function to render HTML
function renderBookPage(Book $book): string
{
    $css = <<<CSS
    <style>
        .book {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .book h2 {
            color: #2c3e50;
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 0.5rem;
        }
        .book p {
            margin: 0.5rem 0;
            line-height: 1.6;
        }
        .old-book {
            color: #e74c3c;
            font-weight: bold;
            background-color: #fdeaea;
            padding: 0.5rem;
            border-radius: 4px;
        }
    </style>
CSS;

    $html = '<!DOCTYPE html>';
    $html .= '<html lang="en">';
    $html .= '<head>';
    $html .= '<meta charset="UTF-8">';
    $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $html .= '<title>' . htmlspecialchars($book->getTitle()) . '</title>';
    $html .= $css;
    $html .= '</head>';
    $html .= '<body>';
    $html .= $book->displayDetails();
    $html .= '</body>';
    $html .= '</html>';
    
    return $html;
}

// Usage example
try {
    $book = new Book(
        'The Little Prince', 
        'Antoine de Saint-Exupéry', 
        1943,
        '978-0156012195'
    );
    
    // Output the full HTML page
    echo renderBookPage($book);
    
    // For API usage
    // header('Content-Type: application/json');
    // echo json_encode($book->toArray());
    
} catch (InvalidArgumentException $e) {
    echo '<div style="color: white; background-color: #e74c3c; padding: 1rem; border-radius: 4px;">';
    echo 'Error: ' . htmlspecialchars($e->getMessage());
    echo '</div>';
}
?>