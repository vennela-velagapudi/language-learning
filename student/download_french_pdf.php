<?php
require('../../fpdf186/fpdf.php');

class PDF extends FPDF {
    // Page header
    function Header() {
        // Logo area with blue background
        $this->SetFillColor(41, 128, 185); // Blue color
        $this->Rect(0, 0, 210, 15, 'F');
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 15, 'FRENCH FOR BEGINNERS', 0, 1, 'C');
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
        $this->SetFillColor(52, 73, 94); // Dark blue-gray
        $this->SetTextColor(255);
        $this->Cell(0, 10, "Lesson $num: $title", 1, 1, 'C', true);
        $this->Ln(3);
    }
    
    // Section
    function SectionTitle($title) {
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(41, 128, 185); // Blue
        $this->Cell(0, 8, $title, 0, 1);
        $this->SetTextColor(0);
    }

    // Vocabulary table with pronunciation
    function PronunciationTable($french, $pronunciation, $english) {
        $this->SetFillColor(240, 240, 240); // Light gray
        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(0);
        
        $w1 = 60; // French column width
        $w2 = 65; // Pronunciation column width
        $w3 = 60; // English column width
        
        // Header
        $this->Cell($w1, 7, 'French', 1, 0, 'C', true);
        $this->Cell($w2, 7, 'Pronunciation', 1, 0, 'C', true);
        $this->Cell($w3, 7, 'English', 1, 1, 'C', true);
        
        // Data
        $this->SetFont('Arial', '', 11);
        $fill = false;
        foreach($french as $i => $f) {
            $this->Cell($w1, 6, $f, 'LR', 0, 'L', $fill);
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
$pdf->SetTextColor(41, 128, 185);
$pdf->Cell(0, 40, '', 0, 1);
$pdf->Cell(0, 20, 'FRENCH FOR BEGINNERS', 0, 1, 'C');
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
$pdf->MultiCell(0, 7, "Begin your French journey with essential phrases and pronunciation.");
$pdf->Ln(3);

$pdf->SectionTitle('French Pronunciation Basics:');
$pdf->SetFont('Arial', '', 11);
$pdf->BulletPoint("Vowels: a (ah), e (uh), i (ee), o (oh), u (oo), ou (oo)");
$pdf->BulletPoint("Nasal Sounds: an/am (ahng), in/im (ang), on/om (ong), un/um (uhng)");
$pdf->BulletPoint("The French R: Made at the back of the throat");
$pdf->BulletPoint("Silent Letters: Many consonants at the end of words (t, s, d, p) are silent");
$pdf->BulletPoint("Liaison: When a word ending with a consonant is followed by a word starting with a vowel, the consonant is pronounced");
$pdf->Ln(3);

$pdf->SectionTitle('Essential Phrases:');
$french = [
    'Bonjour', 
    'Bonsoir', 
    'Au revoir', 
    'Merci', 
    'S\'il vous plait',
    'De rien', 
    'Comment allez-vous?', 
    'Tres bien', 
    'Excusez-moi', 
    'Enchante'
];
$pronunciation = [
    'bong-ZHOOR', 
    'bong-SWAHR', 
    'oh ruh-VWAHR', 
    'mehr-SEE', 
    'seel voo PLEH',
    'duh ree-ANG', 
    'koh-mahnt ah-lay-VOO', 
    'treh bee-ANG', 
    'ex-koo-zay MWAH', 
    'ahn-shahn-TAY'
];
$english = [
    'Hello', 
    'Good evening', 
    'Goodbye',
    'Thank you', 
    'Please',
    'You\'re welcome', 
    'How are you?', 
    'Very well', 
    'Excuse me',
    'Nice to meet you'
];
$pdf->PronunciationTable($french, $pronunciation, $english);

$pdf->SectionTitle('Numbers 0-10:');
$numbers_fr = ['zero', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix'];
$numbers_pron = ['zay-ROH', 'ahn', 'duh', 'twah', 'KAH-truh', 'sank', 'sees', 'set', 'weet', 'nuhf', 'dees'];
$numbers_en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
$pdf->PronunciationTable($numbers_fr, $numbers_pron, $numbers_en);

// Lesson 2
$pdf->AddPage();
$pdf->LessonTitle('2', 'Basic Conversations');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "Learn how to build basic conversations with personal pronouns and essential verbs.");
$pdf->Ln(3);

$pdf->SectionTitle('Personal Pronouns:');
$pronouns = ['Je', 'Tu', 'Il/Elle', 'Nous', 'Vous'];
$pronouns_pron = ['zhuh', 'too', 'eel/el', 'noo', 'voo'];
$pronoun_trans = ['I', 'You (informal)', 'He/She', 'We', 'You (formal/plural)'];
$pdf->PronunciationTable($pronouns, $pronouns_pron, $pronoun_trans);

$pdf->SectionTitle('Basic Verbs (Present Tense):');

// ETRE table
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 7, 'ETRE (to be) - eh-truh', 0, 1);
$pdf->SetFont('Arial', '', 11);

$etre_french = ['Je suis', 'Tu es', 'Il/Elle est', 'Nous sommes', 'Vous etes'];
$etre_pron = ['zhuh swee', 'too eh', 'eel/el eh', 'noo som', 'voo zet'];
$etre_english = ['I am', 'You are', 'He/She is', 'We are', 'You are'];

$pdf->PronunciationTable($etre_french, $etre_pron, $etre_english);

// AVOIR table
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 7, 'AVOIR (to have) - ah-vwahr', 0, 1);
$pdf->SetFont('Arial', '', 11);

$avoir_french = ['J\'ai', 'Tu as', 'Il/Elle a', 'Nous avons', 'Vous avez'];
$avoir_pron = ['zhay', 'too ah', 'eel/el ah', 'noo zah-vong', 'voo zah-vay'];
$avoir_english = ['I have', 'You have', 'He/She has', 'We have', 'You have'];

$pdf->PronunciationTable($avoir_french, $avoir_pron, $avoir_english);

$pdf->SectionTitle('Days of the Week:');
$days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
$days_pron = ['luhn-DEE', 'mahr-DEE', 'mehr-kruh-DEE', 'zhuh-DEE', 'vahn-druh-DEE', 'sahm-DEE', 'dee-MAHNSH'];
$days_trans = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$pdf->PronunciationTable($days, $days_pron, $days_trans);

// Lesson 3
$pdf->AddPage();
$pdf->LessonTitle('3', 'Grammar Basics');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "Master the basic building blocks of French grammar.");
$pdf->Ln(3);

$pdf->SectionTitle('Articles:');
$pdf->SetFont('Arial', '', 11);

// Definite articles
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 7, 'Definite Articles (the):', 0, 1);
$pdf->SetFont('Arial', '', 11);

$def_articles = ['Le', 'La', 'Les', 'L\''];
$def_pron = ['luh', 'lah', 'lay', 'l'];
$def_eng = ['the (masculine singular)', 'the (feminine singular)', 'the (plural)', 'the (before vowel)'];
$pdf->PronunciationTable($def_articles, $def_pron, $def_eng);

// Indefinite articles
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 7, 'Indefinite Articles (a/an/some):', 0, 1);
$pdf->SetFont('Arial', '', 11);

$indef_articles = ['Un', 'Une', 'Des'];
$indef_pron = ['ahn', 'oon', 'day'];
$indef_eng = ['a/an (masculine singular)', 'a/an (feminine singular)', 'some (plural)'];
$pdf->PronunciationTable($indef_articles, $indef_pron, $indef_eng);

$pdf->SectionTitle('Question Words:');
$questions = ['Quoi?', 'Qui?', 'Ou?', 'Quand?', 'Pourquoi?', 'Comment?'];
$questions_pron = ['kwah', 'kee', 'oo', 'kahng', 'poor-KWAH', 'koh-MAHNG'];
$question_trans = ['What?', 'Who?', 'Where?', 'When?', 'Why?', 'How?'];
$pdf->PronunciationTable($questions, $questions_pron, $question_trans);

$pdf->SectionTitle('Common Adjectives:');
$adj_fr = ['Grand', 'Petit', 'Bon', 'Mauvais', 'Beau', 'Nouveau', 'Vieux', 'Jeune'];
$adj_pron = ['grahng', 'puh-TEE', 'bong', 'moh-VEH', 'boh', 'noo-VOH', 'vyuh', 'zhuhn'];
$adj_eng = ['Big/Tall', 'Small/Little', 'Good', 'Bad', 'Beautiful', 'New', 'Old', 'Young'];

$pdf->PronunciationTable($adj_fr, $adj_pron, $adj_eng);

// Lesson 4
$pdf->AddPage();
$pdf->LessonTitle('4', 'Practical Applications');

$pdf->SetFont('Arial', 'I', 11);
$pdf->MultiCell(0, 7, "Put your French to use in real-world situations.");
$pdf->Ln(3);

$pdf->SectionTitle('Restaurant Phrases:');
$restaurant = [
    'Une table pour deux, s\'il vous plait', 
    'L\'addition, s\'il vous plait',
    'Je voudrais commander', 
    'C\'est delicieux',
    'Pouvez-vous m\'apporter...?', 
    'Quel est le plat du jour?'
];
$rest_pron = [
    'oon tahbl poor duh, seel voo pleh',
    'lah-dee-see-ohn, seel voo pleh',
    'zhuh voo-DREH koh-mahn-DAY',
    'seh day-lee-see-UH',
    'poo-vay voo mah-por-TAY',
    'kel eh luh plah doo zhoor'
];
$rest_trans = [
    'A table for two, please', 
    'The check, please',
    'I would like to order', 
    'It is delicious',
    'Could you bring me...?', 
    'What is today\'s special?'
];
$pdf->PronunciationTable($restaurant, $rest_pron, $rest_trans);

$pdf->SectionTitle('Shopping Vocabulary:');
$shopping = [
    'Combien ca coute?', 
    'Avez-vous une autre couleur?',
    'C\'est trop cher', 
    'Moins cher, s\'il vous plait',
    'Quelle est la taille?', 
    'Je le/la prends'
];
$shop_pron = [
    'kom-bee-ANG sah koot',
    'ah-vay voo oon oh-truh koo-LEUR',
    'seh troh shehr',
    'mwang shehr, seel voo pleh',
    'kel eh lah tah-yuh',
    'zhuh luh/lah prahng'
];
$shop_trans = [
    'How much does it cost?', 
    'Do you have another color?',
    'It\'s too expensive', 
    'Less expensive, please',
    'What size is it?', 
    'I\'ll take it'
];
$pdf->PronunciationTable($shopping, $shop_pron, $shop_trans);

$pdf->SectionTitle('Emergency Phrases:');
$emergency = [
    'Au secours!', 
    'J\'ai besoin d\'un medecin',
    'Ou est l\'hopital?', 
    'Appelez la police',
    'Je suis perdu(e)', 
    'J\'ai besoin d\'aide'
];
$emerg_pron = [
    'oh suh-KOOR',
    'zhay buh-zwang duhn mayd-SAN',
    'oo eh loh-pee-TAHL',
    'ah-puh-lay lah poh-LEES',
    'zhuh swee pehr-DOO',
    'zhay buh-zwang dehd'
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
$pdf->Cell(80, 7, 'French (Pronunciation)', 0, 0);
$pdf->Cell(80, 7, 'English', 0, 1);

$pdf->Cell(10, 7, '1.', 0, 0);
$pdf->Cell(70, 7, 'Bonjour (bong-ZHOOR)', 0, 0);
$pdf->Cell(10, 7, 'a.', 0, 0);
$pdf->Cell(70, 7, 'Please', 0, 1);

$pdf->Cell(10, 7, '2.', 0, 0);
$pdf->Cell(70, 7, 'Merci (mehr-SEE)', 0, 0);
$pdf->Cell(10, 7, 'b.', 0, 0);
$pdf->Cell(70, 7, 'Hello', 0, 1);

$pdf->Cell(10, 7, '3.', 0, 0);
$pdf->Cell(70, 7, 'S\'il vous plait (seel voo PLEH)', 0, 0);
$pdf->Cell(10, 7, 'c.', 0, 0);
$pdf->Cell(70, 7, 'Thank you', 0, 1);

$pdf->Cell(10, 7, '4.', 0, 0);
$pdf->Cell(70, 7, 'Comment allez-vous? (koh-mahnt ah-lay-VOO)', 0, 0);
$pdf->Cell(10, 7, 'd.', 0, 0);
$pdf->Cell(70, 7, 'Goodbye', 0, 1);

$pdf->Cell(10, 7, '5.', 0, 0);
$pdf->Cell(70, 7, 'Au revoir (oh ruh-VWAHR)', 0, 0);
$pdf->Cell(10, 7, 'e.', 0, 0);
$pdf->Cell(70, 7, 'How are you?', 0, 1);
$pdf->Ln(5);

$pdf->SectionTitle('Fill in the Blanks:');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, 'Complete the sentences with the correct form of "etre" or "avoir":', 0, 1);
$pdf->Ln(2);
$pdf->BulletPoint("Je _____ etudiant. (I am a student)");
$pdf->BulletPoint("Tu _____ quel age? (How old are you?)");
$pdf->BulletPoint("La maison _____ grande. (The house is big)");
$pdf->BulletPoint("Nous _____ a Paris. (We are in Paris)");
$pdf->Ln(5);

$pdf->SectionTitle('Translation Practice:');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, 'Translate the following sentences to French:', 0, 1);
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
$ref_french = [
    'Bonjour/Au revoir', 
    'S\'il vous plait/Merci',
    'Oui/Non', 
    'Pardon/Excusez-moi',
    'Je ne comprends pas', 
    'Parlez-vous anglais?'
];
$ref_pron = [
    'bong-ZHOOR/oh ruh-VWAHR', 
    'seel voo PLEH/mehr-SEE',
    'wee/nong', 
    'pahr-DONG/ex-koo-zay-MWAH',
    'zhuh nuh kom-PRAHN pah', 
    'pahr-lay voo ahng-GLEH'
];
$ref_phrases = [
    'Hello/Goodbye', 
    'Please/Thank you',
    'Yes/No', 
    'Sorry/Excuse me',
    'I don\'t understand', 
    'Do you speak English?'
];
$pdf->PronunciationTable($ref_french, $ref_pron, $ref_phrases);

$pdf->SectionTitle('Pronunciation Guide:');
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 7, "Remember these key French pronunciation rules:\n");
$pdf->BulletPoint("French has nasal vowels: an/am (ahng), in/im (ang), on/om (ong), un/um (uhng)");
$pdf->BulletPoint("The letter 'r' is pronounced at the back of the throat");
$pdf->BulletPoint("Final consonants are often silent (except c, r, f, l)");
$pdf->BulletPoint("The letter combination 'ou' is pronounced 'oo' as in 'food'");
$pdf->BulletPoint("The letter 'e' without an accent at the end of a word is usually silent");
$pdf->BulletPoint("The letter 'h' is always silent");
$pdf->BulletPoint("When a word ending in a consonant is followed by a word beginning with a vowel, the consonant is pronounced (liaison)");
$pdf->BulletPoint("The stress is usually on the last syllable of a word or phrase");
$pdf->BulletPoint("The letters 'th' are pronounced simply as 't'");
$pdf->BulletPoint("The letter 'u' is pronounced by rounding your lips as if to say 'oo' but saying 'ee'");

// Output PDF
$pdf->Output('D', 'French_Course.pdf');
exit;
?>