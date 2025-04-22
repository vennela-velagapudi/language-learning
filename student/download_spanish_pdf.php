<?php
require('../../fpdf186/fpdf.php');

class PDF extends FPDF {
    // Page header
    function Header() {
        // Logo area with orange background
        $this->SetFillColor(230, 126, 34); // Orange color
        $this->Rect(0, 0, 210, 15, 'F');
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 15, 'SPANISH FOR BEGINNERS', 0, 1, 'C');
        $this->Ln(5);
    }

    // Page footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
    
    // Title
    function LessonTitle($num, $title) {
        $this->SetFont('Arial', 'B', 14);
        $this->SetFillColor(52, 152, 219); // Blue
        $this->SetTextColor(255);
        $this->Cell(0, 10, "Lesson $num: $title", 1, 1, 'C', true);
        $this->Ln(3);
    }
    
    // Section
    function SectionTitle($title) {
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(41, 128, 185); // Darker blue
        $this->Cell(0, 8, $title, 0, 1);
        $this->SetTextColor(0);
    }

    // Vocabulary table with pronunciation
    function PronunciationTable($spanish, $pronunciation, $english) {
        $this->SetFillColor(240, 240, 240); // Light gray
        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(0);
        
        $w1 = 60; // Spanish column width
        $w2 = 65; // Pronunciation column width
        $w3 = 60; // English column width
        
        // Header
        $this->Cell($w1, 7, 'Spanish', 1, 0, 'C', true);
        $this->Cell($w2, 7, 'Pronunciation', 1, 0, 'C', true);
        $this->Cell($w3, 7, 'English', 1, 1, 'C', true);
        
        // Data
        $this->SetFont('Arial', '', 11);
        $fill = false;
        foreach($spanish as $i => $s) {
            $this->Cell($w1, 6, $s, 'LR', 0, 'L', $fill);
            $this->Cell($w2, 6, $pronunciation[$i], 'LR', 0, 'L', $fill);
            $this->Cell($w3, 6, $english[$i], 'LR', 1, 'L', $fill);
            $fill = !$fill;
        }
        // Closing line
        $this->Cell($w1 + $w2 + $w3, 0, '', 'T');
        $this->Ln(5);
    }
    
    // Bullet points - proper bullet character
    function BulletPoint($txt) {
        $this->SetFont('ZapfDingbats', '', 10);
        $this->Cell(5, 6, chr(108), 0, 0);
        $this->SetFont('Arial', '', 11);
        $this->MultiCell(0, 6, $txt);
    }
}

// Create PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetMargins(15, 15, 15);

// Cover Page
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 24);
$pdf->SetTextColor(52, 152, 219);
$pdf->Cell(0, 40, '', 0, 1);
$pdf->Cell(0, 20, 'SPANISH FOR BEGINNERS', 0, 1, 'C');
$pdf->SetFont('Arial', '', 14);
$pdf->SetTextColor(0);
$pdf->Cell(0, 10, 'A Comprehensive Guide', 0, 1, 'C');
$pdf->Cell(0, 20, '', 0, 1);
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, 'Each lesson on its own page', 0, 1, 'C');
$pdf->Cell(0, 10, 'With pronunciation guides for correct reading', 0, 1, 'C');

// Lesson 1
$pdf->AddPage();
$pdf->LessonTitle('1', 'Getting Started');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "Begin your Spanish journey with essential phrases and pronunciation.");
$pdf->Ln(3);

$pdf->SectionTitle('Alphabet & Pronunciation:');
$pdf->SetFont('Arial', '', 11);
$pdf->BulletPoint("Vowels: a (ah), e (eh), i (ee), o (oh), u (oo)");
$pdf->BulletPoint("Consonants: similar to English, with special attention to:");
$pdf->SetX($pdf->GetX() + 10);
$pdf->BulletPoint("n with tilde (n) = like 'ny' in canyon");
$pdf->SetX($pdf->GetX() + 10);
$pdf->BulletPoint("ll = like 'y' in yes");
$pdf->SetX($pdf->GetX() + 10);
$pdf->BulletPoint("rr = strongly rolled r");
$pdf->Ln(3);

$pdf->SectionTitle('Essential Phrases:');
$spanish = [
    'Hola', 
    'Buenos dias', 
    'Buenas tardes', 
    'Buenas noches', 
    'Gracias', 
    'Por favor',
    'De nada', 
    'Como estas?', 
    'Muy bien', 
    'Adios'
];
$pronunciation = [
    'OH-lah', 
    'BWAY-nohs DEE-ahs', 
    'BWAY-nahs TAR-dace', 
    'BWAY-nahs NO-chase', 
    'GRAH-see-ahs', 
    'por fah-VOR',
    'day NAH-dah', 
    'KO-mo es-TAS', 
    'moo-ee bee-EN', 
    'ah-dee-OS'
];
$english = [
    'Hello', 
    'Good morning', 
    'Good afternoon',
    'Good night', 
    'Thank you', 
    'Please',
    'You\'re welcome', 
    'How are you?', 
    'Very well', 
    'Goodbye'
];
$pdf->PronunciationTable($spanish, $pronunciation, $english);

$pdf->SectionTitle('Numbers 0-10:');
$numbers_es = ['cero', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez'];
$numbers_pron = ['SAY-ro', 'OO-no', 'dohs', 'trace', 'KWAH-tro', 'SEEN-ko', 'say-ees', 'see-EH-tay', 'OH-cho', 'noo-EH-vay', 'dee-ETH'];
$numbers_en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
$pdf->PronunciationTable($numbers_es, $numbers_pron, $numbers_en);

// Lesson 2
$pdf->AddPage();
$pdf->LessonTitle('2', 'Basic Conversations');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "Learn how to build basic conversations with personal pronouns and essential verbs.");
$pdf->Ln(3);

$pdf->SectionTitle('Personal Pronouns:');
$pronouns = ['Yo', 'Tu', 'El/Ella', 'Nosotros/as', 'Ustedes'];
$pronouns_pron = ['yoh', 'too', 'el/EY-ya', 'no-SOH-trohs/trahs', 'oo-STEH-dace'];
$pronoun_trans = ['I', 'You (informal)', 'He/She', 'We', 'You all (formal)'];
$pdf->PronunciationTable($pronouns, $pronouns_pron, $pronoun_trans);

$pdf->SectionTitle('Basic Verbs (Present Tense):');

// SER table
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 7, 'SER (to be - permanent) - sayr', 0, 1);
$pdf->SetFont('Arial', '', 11);

$ser_spanish = ['Yo soy', 'Tu eres', 'El/Ella es', 'Nosotros somos', 'Ustedes son'];
$ser_pron = ['yoh soy', 'too EH-race', 'el/EY-ya ess', 'no-SOH-trohs SOH-mohs', 'oo-STEH-dace sohn'];
$ser_english = ['I am', 'You are', 'He/She is', 'We are', 'You all are'];

$pdf->PronunciationTable($ser_spanish, $ser_pron, $ser_english);

// ESTAR table
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 7, 'ESTAR (to be - temporary) - es-TAR', 0, 1);
$pdf->SetFont('Arial', '', 11);

$estar_spanish = ['Yo estoy', 'Tu estas', 'El/Ella esta', 'Nosotros estamos', 'Ustedes estan'];
$estar_pron = ['yoh es-TOY', 'too es-TAHS', 'el/EY-ya es-TAH', 'no-SOH-trohs es-TAH-mohs', 'oo-STEH-dace es-TAHN'];
$estar_english = ['I am', 'You are', 'He/She is', 'We are', 'You all are'];

$pdf->PronunciationTable($estar_spanish, $estar_pron, $estar_english);

$pdf->SectionTitle('Days of the Week:');
$days = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
$days_pron = ['LOO-nace', 'MAR-tace', 'mee-AIR-ko-lace', 'HWAY-vace', 'vee-AIR-nace', 'SAH-bah-doh', 'doh-MEEN-goh'];
$days_trans = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$pdf->PronunciationTable($days, $days_pron, $days_trans);

// Lesson 3
$pdf->AddPage();
$pdf->LessonTitle('3', 'Grammar Basics');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "Master the basic building blocks of Spanish grammar.");
$pdf->Ln(3);

$pdf->SectionTitle('Articles:');
$pdf->SetFont('Arial', '', 11);

// Definite articles
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 7, 'Definite Articles (the):', 0, 1);
$pdf->SetFont('Arial', '', 11);

$def_articles = ['El', 'La', 'Los', 'Las'];
$def_pron = ['el', 'lah', 'lohs', 'lahs'];
$def_eng = ['the (masculine singular)', 'the (feminine singular)', 'the (masculine plural)', 'the (feminine plural)'];
$pdf->PronunciationTable($def_articles, $def_pron, $def_eng);

// Indefinite articles
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 7, 'Indefinite Articles (a/an/some):', 0, 1);
$pdf->SetFont('Arial', '', 11);

$indef_articles = ['Un', 'Una', 'Unos', 'Unas'];
$indef_pron = ['oon', 'OO-nah', 'OO-nohs', 'OO-nahs'];
$indef_eng = ['a/an (masculine singular)', 'a/an (feminine singular)', 'some (masculine plural)', 'some (feminine plural)'];
$pdf->PronunciationTable($indef_articles, $indef_pron, $indef_eng);

$pdf->SectionTitle('Question Words:');
$questions = ['Que?', 'Quien?', 'Donde?', 'Cuando?', 'Por que?', 'Como?'];
$questions_pron = ['kay', 'kee-EN', 'DON-day', 'KWAN-doh', 'por KAY', 'KO-mo'];
$question_trans = ['What?', 'Who?', 'Where?', 'When?', 'Why?', 'How?'];
$pdf->PronunciationTable($questions, $questions_pron, $question_trans);

$pdf->SectionTitle('Common Adjectives:');
$adj_span = ['Grande', 'Pequeno', 'Alto', 'Bajo', 'Bonito', 'Feo', 'Nuevo', 'Viejo'];
$adj_pron = ['GRAHN-day', 'pay-KAY-nyoh', 'AHL-toh', 'BAH-hoh', 'boh-NEE-toh', 'FAY-oh', 'noo-AY-voh', 'vee-AY-hoh'];
$adj_eng = ['Big/Large', 'Small/Little', 'Tall', 'Short', 'Pretty', 'Ugly', 'New', 'Old'];

$pdf->PronunciationTable($adj_span, $adj_pron, $adj_eng);

// Lesson 4
$pdf->AddPage();
$pdf->LessonTitle('4', 'Practical Applications');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "Put your Spanish to use in real-world situations.");
$pdf->Ln(3);

$pdf->SectionTitle('Restaurant Phrases:');
$restaurant = [
    'Tiene una mesa para dos?', 
    'La cuenta, por favor',
    'Quisiera ordenar', 
    'Esta delicioso',
    'Me puede traer...?', 
    'Cual es el especial hoy?'
];
$rest_pron = [
    'tee-EH-nay OO-nah MAY-sah PAH-rah dohs',
    'lah KWEN-tah, por fah-VOR',
    'kee-see-EH-rah or-day-NAR',
    'es-TAH day-lee-see-OH-soh',
    'may PWAY-day trah-AIR',
    'kwal es el es-pay-see-AL oy'
];
$rest_trans = [
    'Do you have a table for two?', 
    'The check, please',
    'I would like to order', 
    'It is delicious',
    'Could you bring me...?', 
    'What is today\'s special?'
];
$pdf->PronunciationTable($restaurant, $rest_pron, $rest_trans);

$pdf->SectionTitle('Shopping Vocabulary:');
$shopping = [
    'Cuanto cuesta?', 
    'Tiene en otro color?',
    'Es demasiado caro', 
    'Mas barato, por favor',
    'Que talla es?', 
    'Me lo/la llevo'
];
$shop_pron = [
    'KWAN-toh KWES-tah',
    'tee-EH-nay en OH-troh koh-LOR',
    'es day-mah-see-AH-doh KAH-roh',
    'mahs bah-RAH-toh, por fah-VOR',
    'kay TAH-yah es',
    'may loh/lah LYAY-voh'
];
$shop_trans = [
    'How much does it cost?', 
    'Do you have it in another color?',
    'It\'s too expensive', 
    'Cheaper, please',
    'What size is it?', 
    'I\'ll take it'
];
$pdf->PronunciationTable($shopping, $shop_pron, $shop_trans);

$pdf->SectionTitle('Emergency Phrases:');
$emergency = [
    'Ayuda!', 
    'Necesito un medico',
    'Donde esta el hospital?', 
    'Llame a la policia',
    'Estoy perdido/a', 
    'Necesito ayuda'
];
$emerg_pron = [
    'ah-YOO-dah',
    'nay-say-SEE-toh oon MAY-dee-koh',
    'DON-day es-TAH el os-pee-TAHL',
    'YAH-may ah lah poh-lee-SEE-ah',
    'es-TOY pair-DEE-doh/dah',
    'nay-say-SEE-toh ah-YOO-dah'
];
$emerg_trans = [
    'Help!', 
    'I need a doctor',
    'Where is the hospital?', 
    'Call the police',
    'I am lost', 
    'I need help'
];
$pdf->PronunciationTable($emergency, $emerg_pron, $emerg_trans);

// Practice Exercises
$pdf->AddPage();
$pdf->LessonTitle('5', 'Practice Exercises');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "Test your knowledge with these exercises covering all lessons.");
$pdf->Ln(3);

$pdf->SectionTitle('Matching Exercise:');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(80, 7, 'Spanish (Pronunciation)', 0, 0);
$pdf->Cell(80, 7, 'English', 0, 1);

$pdf->Cell(10, 7, '1.', 0, 0);
$pdf->Cell(70, 7, 'Buenos dias (BWAY-nohs DEE-ahs)', 0, 0);
$pdf->Cell(10, 7, 'a.', 0, 0);
$pdf->Cell(70, 7, 'Please', 0, 1);

$pdf->Cell(10, 7, '2.', 0, 0);
$pdf->Cell(70, 7, 'Gracias (GRAH-see-ahs)', 0, 0);
$pdf->Cell(10, 7, 'b.', 0, 0);
$pdf->Cell(70, 7, 'Good morning', 0, 1);

$pdf->Cell(10, 7, '3.', 0, 0);
$pdf->Cell(70, 7, 'Por favor (por fah-VOR)', 0, 0);
$pdf->Cell(10, 7, 'c.', 0, 0);
$pdf->Cell(70, 7, 'Thank you', 0, 1);

$pdf->Cell(10, 7, '4.', 0, 0);
$pdf->Cell(70, 7, 'Como estas? (KO-mo es-TAS)', 0, 0);
$pdf->Cell(10, 7, 'd.', 0, 0);
$pdf->Cell(70, 7, 'Goodbye', 0, 1);

$pdf->Cell(10, 7, '5.', 0, 0);
$pdf->Cell(70, 7, 'Adios (ah-dee-OS)', 0, 0);
$pdf->Cell(10, 7, 'e.', 0, 0);
$pdf->Cell(70, 7, 'How are you?', 0, 1);
$pdf->Ln(5);

$pdf->SectionTitle('Fill in the Blanks:');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, 'Complete the sentences with the correct form of "ser" or "estar":', 0, 1);
$pdf->Ln(2);
$pdf->BulletPoint("Yo _____ estudiante. (I am a student)");
$pdf->BulletPoint("Como _____ tu? (How are you?)");
$pdf->BulletPoint("La casa _____ grande. (The house is big)");
$pdf->BulletPoint("Nosotros _____ en Madrid. (We are in Madrid)");
$pdf->Ln(5);

$pdf->SectionTitle('Translation Practice:');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, 'Translate the following sentences to Spanish:', 0, 1);
$pdf->Ln(2);
$pdf->BulletPoint("My name is John.");
$pdf->BulletPoint("I am twenty years old.");
$pdf->BulletPoint("Where is the restaurant?");
$pdf->BulletPoint("I would like a coffee, please.");
$pdf->BulletPoint("How much does it cost?");

// Reference Page
$pdf->AddPage();
$pdf->LessonTitle('', 'Quick Reference');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "A handy summary of the most important phrases and vocabulary with pronunciation.");
$pdf->Ln(3);

$pdf->SectionTitle('Essential Phrases at a Glance:');
$ref_spanish = [
    'Hola/Adios', 
    'Por favor/Gracias',
    'Si/No', 
    'Perdon/Disculpe',
    'No entiendo', 
    'Habla ingles?'
];
$ref_pron = [
    'OH-lah/ah-dee-OS', 
    'por fah-VOR/GRAH-see-ahs',
    'see/no', 
    'pair-DON/dees-KOOL-pay',
    'no en-tee-EN-doh', 
    'AH-blah een-GLAYS'
];
$ref_phrases = [
    'Hello/Goodbye', 
    'Please/Thank you',
    'Yes/No', 
    'Excuse me',
    'I don\'t understand', 
    'Do you speak English?'
];
$pdf->PronunciationTable($ref_spanish, $ref_pron, $ref_phrases);

$pdf->SectionTitle('Pronunciation Guide:');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 7, "Remember these key pronunciation rules:\n");
$pdf->BulletPoint("A - always pronounced 'ah' as in 'father'");
$pdf->BulletPoint("E - always pronounced 'eh' as in 'get'");
$pdf->BulletPoint("I - always pronounced 'ee' as in 'see'");
$pdf->BulletPoint("O - always pronounced 'oh' as in 'go'");
$pdf->BulletPoint("U - always pronounced 'oo' as in 'too'");
$pdf->BulletPoint("N with tilde - pronounced like 'ny' in 'canyon'");
$pdf->BulletPoint("H - is always silent");
$pdf->BulletPoint("J - pronounced like 'h' in 'house'");
$pdf->BulletPoint("LL - pronounced like 'y' in 'yellow'");
$pdf->BulletPoint("The stress is usually on the second-to-last syllable, unless there is an accent mark");

// Output PDF
$pdf->Output('D', 'Spanish_Course.pdf');
exit;
?>