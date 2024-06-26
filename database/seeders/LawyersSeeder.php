<?php

namespace Database\Seeders;

use App\Models\Lawyer;
use App\Models\Lawyers;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LawyersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $lawyers = [
            ['Atty. Maria Cruz', 'maria.cruz@email.com, +63 912 345 6789', 'Family Law, Civil Law, Property Law', 'San Fernando, La Union', 8],
            ['Atty. Juanito Reyes', 'juanito.reyes@email.com, +63 922 123 4567', 'Family Law, Criminal Law, Immigration Law', 'Vigan City, Ilocos Sur', 10],
            ['Atty. Lorna Garcia', 'lorna.garcia@email.com, +63 933 234 5678', 'Family Law, Labor Law, Corporate Law', 'Dagupan City, Pangasinan', 12],
            ['Atty. Rafaela Santos', 'rafaela.santos@email.com, +63 945 345 6789', 'Family Law, Environmental Law, Real Estate Law', 'Urdaneta City, Pangasinan', 9],
            ['Atty. Fernando Reyes', 'fernando.reyes@email.com, +63 955 456 7890', 'Family Law, Intellectual Property Law, Tax Law', 'San Carlos City, Pangasinan', 11],
            ['Atty. Maria Theresa Cruz', 'maria.theresa.cruz@email.com, +63 977 567 8901', 'Family Law, Commercial Law, Employment Law', 'San Fernando City, La Union', 7],
            ['Atty. Antonio Garcia', 'antonio.garcia@email.com, +63 988 678 9012', 'Family Law, Administrative Law, Banking Law', 'Laoag City, Ilocos Norte', 15],
            ['Atty. Andrea Reyes', 'andrea.reyes@email.com, +63 933 789 0123', 'Family Law, Maritime Law, Insurance Law', 'Alaminos City, Pangasinan', 13],
            ['Atty. Eduardo Santos', 'eduardo.santos@email.com, +63 977 890 1234', 'Family Law, Constitutional Law, Immigration Law', 'Batac City, Ilocos Norte', 10],
            ['Atty. Sofia Garcia', 'sofia.garcia@email.com, +63 955 901 2345', 'Family Law, International Law, Cyber Law', 'Candon City, Ilocos Sur', 8],
            ['Atty. Juanita Reyes', 'juanita.reyes@email.com, +63 912 345 6789', 'Family Law, Civil Law, Property Law', 'San Fernando, La Union', 11],
            ['Atty. Manuel Cruz', 'manuel.cruz@email.com, +63 922 123 4567', 'Family Law, Criminal Law, Immigration Law', 'Vigan City, Ilocos Sur', 9],
            ['Atty. Maricar Garcia', 'maricar.garcia@email.com, +63 933 234 5678', 'Family Law, Labor Law, Corporate Law', 'Dagupan City, Pangasinan', 13],
            ['Atty. Roberto Santos', 'roberto.santos@email.com, +63 945 345 6789', 'Family Law, Environmental Law, Real Estate Law', 'Urdaneta City, Pangasinan', 10],
            ['Atty. Maria Cristina Reyes', 'maria.cristina.reyes@email.com, +63 955 456 7890', 'Family Law, Intellectual Property Law, Tax Law', 'San Carlos City, Pangasinan', 12],
            ['Atty. Jose Garcia', 'jose.garcia@email.com, +63 977 567 8901', 'Family Law, Commercial Law, Employment Law', 'San Fernando City, La Union', 8],
            ['Atty. Carla Santos', 'carla.santos@email.com, +63 988 678 9012', 'Family Law, Administrative Law, Banking Law', 'Laoag City, Ilocos Norte', 14],
            ['Atty. Miguel Reyes', 'miguel.reyes@email.com, +63 933 789 0123', 'Family Law, Maritime Law, Insurance Law', 'Alaminos City, Pangasinan', 11],
            ['Atty. Anna Garcia', 'anna.garcia@email.com, +63 977 890 1234', 'Family Law, Constitutional Law, Immigration Law', 'Batac City, Ilocos Norte', 12],
            ['Atty. Ricardo Santos', 'ricardo.santos@email.com, +63 955 901 2345', 'Family Law, International Law, Cyber Law', 'Candon City, Ilocos Sur', 9],
            ['Atty. Andrea Cruz', 'andrea.cruz@email.com, +63 912 345 6789', 'Family Law, Civil Law, Property Law', 'Baguio City, Benguet', 8],
            ['Atty. Alejandro Reyes', 'alejandro.reyes@email.com, +63 922 123 4567', 'Family Law, Criminal Law, Immigration Law', 'Tarlac City, Tarlac', 10],
            ['Atty. Luz Garcia', 'luz.garcia@email.com, +63 933 234 5678', 'Family Law, Labor Law, Corporate Law', 'Batangas City, Batangas', 12],
            ['Atty. Daniel Santos', 'daniel.santos@email.com, +63 945 345 6789', 'Family Law, Environmental Law, Real Estate Law', 'Cabanatuan City, Nueva Ecija', 9],
            ['Atty. Maria Victoria Reyes', 'maria.victoria.reyes@email.com, +63 955 456 7890', 'Family Law, Intellectual Property Law, Tax Law', 'Calamba City, Laguna', 11],
            ['Atty. Juan Garcia', 'juan.garcia@email.com, +63 977 567 8901', 'Family Law, Commercial Law, Employment Law', 'Lipa City, Batangas', 7],
            ['Atty. Maria Clara Santos', 'maria.clara.santos@email.com, +63 988 678 9012', 'Family Law, Administrative Law, Banking Law', 'San Pablo City, Laguna', 15],
            ['Atty. Josefa Reyes', 'josefa.reyes@email.com, +63 933 789 0123', 'Family Law, Maritime Law, Insurance Law', 'Olongapo City, Zambales', 13],
            ['Atty. Antonio Santos', 'antonio.santos@email.com, +63 977 890 1234', 'Family Law, Constitutional Law, Immigration Law', 'Balanga City, Bataan', 10],
            ['Atty. Maria Consuelo Reyes', 'maria.consuelo.reyes@email.com, +63 955 901 2345', 'Family Law, International Law, Cyber Law', 'Malolos City, Bulacan', 8],
            ['Atty. Miguel Garcia', 'miguel.garcia@email.com, +63 912 345 6789', 'Family Law, Civil Law, Property Law', 'Bacolod City, Negros Occidental', 11],
            ['Atty. Maria Luisa Santos', 'maria.luisa.santos@email.com, +63 922 123 4567', 'Family Law, Criminal Law, Immigration Law', 'Dumaguete City, Negros Oriental', 9],
            ['Atty. Juanito Reyes', 'juanito.reyes@email.com, +63 933 234 5678', 'Family Law, Labor Law, Corporate Law', 'Tagbilaran City, Bohol', 13],
            ['Atty. Rosa Garcia', 'rosa.garcia@email.com, +63 945 345 6789', 'Family Law, Environmental Law, Real Estate Law', 'Cebu City, Cebu', 10],
            ['Atty. Manuel Santos', 'manuel.santos@email.com, +63 955 456 7890', 'Family Law, Intellectual Property Law, Tax Law', 'Ormoc City, Leyte', 12],
            ['Atty. Consuelo Reyes', 'consuelo.reyes@email.com, +63 977 567 8901', 'Family Law, Commercial Law, Employment Law', 'Tacloban City, Leyte', 8],
            ['Atty. Domingo Garcia', 'domingo.garcia@email.com, +63 988 678 9012', 'Family Law, Administrative Law, Banking Law', 'Roxas City, Capiz', 14],
            ['Atty. Maria Teresa Santos', 'maria.teresa.santos@email.com, +63 933 789 0123', 'Family Law, Maritime Law, Insurance Law', 'Iloilo City, Iloilo', 11],
            ['Atty. Jesus Reyes', 'jesus.reyes@email.com, +63 977 890 1234', 'Family Law, Constitutional Law, Immigration Law', 'Kalibo, Aklan', 12],
            ['Atty. Isabel Garcia', 'isabel.garcia@email.com, +63 955 901 2345', 'Family Law, International Law, Cyber Law', 'Sagay City, Negros Occidental', 9],
            ['Atty. Eduardo Reyes', 'eduardo.reyes@email.com, +63 912 345 6789', 'Family Law, Civil Law, Property Law', 'Davao City, Davao del Sur', 10],
            ['Atty. Maria Luisa Garcia', 'maria.luisa.garcia@email.com, +63 922 123 4567', 'Family Law, Criminal Law, Immigration Law', 'General Santos City, South Cotabato', 8],
            ['Atty. Josefa Reyes', 'josefa.reyes@email.com, +63 933 234 5678', 'Family Law, Labor Law, Corporate Law', 'Cagayan de Oro City, Misamis Oriental', 12],
            ['Atty. Pedro Santos', 'pedro.santos@email.com, +63 945 345 6789', 'Family Law, Environmental Law, Real Estate Law', 'Zamboanga City, Zamboanga del Sur', 9],
            ['Atty. Maria Teresa Reyes', 'maria.teresa.reyes@email.com, +63 955 456 7890', 'Family Law, Intellectual Property Law, Tax Law', 'Butuan City, Agusan del Norte', 11],
            ['Atty. Gabriel Garcia', 'gabriel.garcia@email.com, +63 977 567 8901', 'Family Law, Commercial Law, Employment Law', 'Digos City, Davao del Sur', 7],
            ['Atty. Consuelo Santos', 'consuelo.santos@email.com, +63 988 678 9012', 'Family Law, Administrative Law, Banking Law', 'Iligan City, Lanao del Norte', 15],
            ['Atty. Jose Reyes', 'jose.reyes@email.com, +63 933 789 0123', 'Family Law, Maritime Law, Insurance Law', 'Pagadian City, Zamboanga del Sur', 13],
            ['Atty. Isabel Santos', 'isabel.santos@email.com, +63 977 890 1234', 'Family Law, Constitutional Law, Immigration Law', 'Kidapawan City, Cotabato', 10],
            ['Atty. Ricardo Reyes', 'ricardo.reyes@email.com, +63 955 901 2345', 'Family Law, International Law, Cyber Law', 'Mati City, Davao Oriental', 8],
            ['Atty. Maria Clara Garcia', 'maria.clara.garcia@email.com, +63 912 345 6789', 'Family Law, Civil Law, Property Law', 'Dipolog City, Zamboanga del Norte', 11],
            ['Atty. Alejandro Santos', 'alejandro.santos@email.com, +63 922 123 4567', 'Family Law, Criminal Law, Immigration Law', 'Malaybalay City, Bukidnon', 9],
            ['Atty. Luz Reyes', 'luz.reyes@email.com, +63 933 234 5678', 'Family Law, Labor Law, Corporate Law', 'Koronadal City, South Cotabato', 13],
            ['Atty. Daniel Garcia', 'daniel.garcia@email.com, +63 945 345 6789', 'Family Law, Environmental Law, Real Estate Law', 'Tagum City, Davao del Norte', 10],
            ['Atty. Maria Victoria Santos', 'maria.victoria.santos@email.com, +63 955 456 7890', 'Family Law, Intellectual Property Law, Tax Law', 'Valencia City, Bukidnon', 12],
            ['Atty. Juanito Reyes', 'juanito.reyes@email.com, +63 977 567 8901', 'Family Law, Commercial Law, Employment Law', 'Oroquieta City, Misamis Occidental', 8],
            ['Atty. Maria Cristina Garcia', 'maria.cristina.garcia@email.com, +63 988 678 9012', 'Family Law, Administrative Law, Banking Law', 'Butuan City, Agusan del Norte', 14],
            ['Atty. Josefa Santos', 'josefa.santos@email.com, +63 933 789 0123', 'Family Law, Maritime Law, Insurance Law', 'Tandag City, Surigao del Sur', 11],
            ['Atty. Gabriel Reyes', 'gabriel.reyes@email.com, +63 977 890 1234', 'Family Law, Constitutional Law, Immigration Law', 'Malabang, Lanao del Sur', 12],
            ['Atty. Luz Santos', 'luz.santos@email.com, +63 955 901 2345', 'Family Law, International Law, Cyber Law', 'Surigao City, Surigao del Norte', 9],
            ['Atty. Eduardo Reyes', 'eduardo.reyes@email.com, +63 912 345 6789', 'Family Law, Civil Law, Property Law', 'Digos City, Davao del Sur', 10],
            ['Atty. Maria Luisa Garcia', 'maria.luisa.garcia@email.com, +63 922 123 4567', 'Family Law, Criminal Law, Immigration Law', 'Dipolog City, Zamboanga del Norte', 8],
            ['Atty. Josefa Reyes', 'josefa.reyes@email.com, +63 933 234 5678', 'Family Law, Labor Law, Corporate Law', 'Mati City, Davao Oriental', 12],
            ['Atty. Pedro Santos', 'pedro.santos@email.com, +63 945 345 6789', 'Family Law, Environmental Law, Real Estate Law', 'Kidapawan City, Cotabato', 9],
            ['Atty. Maria Teresa Reyes', 'maria.teresa.reyes@email.com, +63 955 456 7890', 'Family Law, Intellectual Property Law, Tax Law', 'Malaybalay City, Bukidnon', 11],
            ['Atty. Gabriel Garcia', 'gabriel.garcia@email.com, +63 977 567 8901', 'Family Law, Commercial Law, Employment Law', 'Koronadal City, South Cotabato', 7],
            ['Atty. Consuelo Santos', 'consuelo.santos@email.com, +63 988 678 9012', 'Family Law, Administrative Law, Banking Law', 'Tagum City, Davao del Norte', 15],
            ['Atty. Jose Reyes', 'jose.reyes@email.com, +63 933 789 0123', 'Family Law, Maritime Law, Insurance Law', 'Valencia City, Bukidnon', 13],
            ['Atty. Isabel Santos', 'isabel.santos@email.com, +63 977 890 1234', 'Family Law, Constitutional Law, Immigration Law', 'Oroquieta City, Misamis Occidental', 10],
            ['Atty. Ricardo Reyes', 'ricardo.reyes@email.com, +63 955 901 2345', 'Family Law, International Law, Cyber Law', 'Tandag City, Surigao del Sur', 8],
            ['Atty. Maria Cristina Santos', 'maria.cristina.santos@email.com, +63 912 345 6789', 'Family Law, Civil Law, Property Law', 'Quezon City, Metro Manila', 11],
            ['Atty. Alejandro Reyes', 'alejandro.reyes@email.com, +63 922 123 4567', 'Family Law, Criminal Law, Immigration Law', 'Makati City, Metro Manila', 9],
            ['Atty. Luz Garcia', 'luz.garcia@email.com, +63 933 234 5678', 'Family Law, Labor Law, Corporate Law', 'Pasig City, Metro Manila', 13],
            ['Atty. Daniel Santos', 'daniel.santos@email.com, +63 945 345 6789', 'Family Law, Environmental Law, Real Estate Law', 'Mandaluyong City, Metro Manila', 10],
            ['Atty. Maria Victoria Reyes', 'maria.victoria.reyes@email.com, +63 955 456 7890', 'Family Law, Intellectual Property Law, Tax Law', 'Manila City, Metro Manila', 12],
            ['Atty. Juan Garcia', 'juan.garcia@email.com, +63 977 567 8901', 'Family Law, Commercial Law, Employment Law', 'Caloocan City, Metro Manila', 8],
            ['Atty. Maria Clara Santos', 'maria.clara.santos@email.com, +63 988 678 9012', 'Family Law, Administrative Law, Banking Law', 'Pasay City, Metro Manila', 14],
            ['Atty. Jose Reyes', 'jose.reyes@email.com, +63 933 789 0123', 'Family Law, Maritime Law, Insurance Law', 'Taguig City, Metro Manila', 13],
            ['Atty. Isabel Garcia', 'isabel.garcia@email.com, +63 977 890 1234', 'Family Law, Constitutional Law, Immigration Law', 'Marikina City, Metro Manila', 10],
            ['Atty. Ricardo Reyes', 'ricardo.reyes@email.com, +63 955 901 2345', 'Family Law, International Law, Cyber Law', 'Malabon City, Metro Manila', 8],
            ['Atty. Mateo Cruz', 'mateo.cruz@email.com, +63 912 345 6789', 'Family Law, Civil Law, Property Law', 'Baguio City, Benguet', 11],
            ['Atty. Veronica Reyes', 'veronica.reyes@email.com, +63 922 123 4567', 'Family Law, Criminal Law, Immigration Law', 'Baguio City, Benguet', 9],
            ['Atty. Sofia Garcia', 'sofia.garcia@email.com, +63 933 234 5678', 'Family Law, Labor Law, Corporate Law', 'Baguio City, Benguet', 13],
            ['Atty. Diego Santos', 'diego.santos@email.com, +63 945 345 6789', 'Family Law, Environmental Law, Real Estate Law', 'Baguio City, Benguet', 10],
            ['Atty. Maria Theresa Reyes', 'maria.theresa.reyes@email.com, +63 955 456 7890', 'Family Law, Intellectual Property Law, Tax Law', 'Baguio City, Benguet', 12],
            ['Atty. Miguel Garcia', 'miguel.garcia@email.com, +63 977 567 8901', 'Family Law, Civil Law, Property Law', 'San Fernando City, La Union', 8],
            ['Atty. Rosario Reyes', 'rosario.reyes@email.com, +63 988 678 9012', 'Family Law, Criminal Law, Immigration Law', 'San Fernando City, La Union', 14],
            ['Atty. Juanito Santos', 'juanito.santos@email.com, +63 933 789 0123', 'Family Law, Labor Law, Corporate Law', 'San Fernando City, La Union', 12],
            ['Atty. Clara Reyes', 'clara.reyes@email.com, +63 977 890 1234', 'Family Law, Environmental Law, Real Estate Law', 'San Fernando City, La Union', 9],
            ['Atty. Francisco Garcia', 'francisco.garcia@email.com, +63 955 901 2345', 'Family Law, Intellectual Property Law, Tax Law', 'Bacnotan, La Union', 11],
            ['Atty. Juan Dela Cruz', 'juan.delacruz@email.com, +63 922 123 4567', 'Family Law, Criminal Law, Tribal Law', 'Bontoc, Mountain Province', 11],
            ['Atty. Lorna Garcia', 'lorna.garcia@email.com, +63 933 234 5678', 'Family Law, Environmental Law, Land Rights', 'Basco, Batanes', 13],
            ['Atty. Rafael Reyes', 'rafael.reyes@email.com, +63 945 345 6789', 'Family Law, Customary Law, Community Development', 'Jolo, Sulu', 10],
            ['Atty. Maria Theresa Cruz', 'maria.theresa.cruz@email.com, +63 955 456 7890', 'Family Law, Human Rights Law, Access to Justice', 'Tawi-Tawi, ARMM', 12],
            ['Atty. Antonio Garcia', 'antonio.garcia@email.com, +63 977 567 8901', 'Family Law, Agrarian Law, Community Disputes', 'Sagada, Mountain Province', 8],
            ['Atty. Andrea Reyes', 'andrea.reyes@email.com, +63 988 678 9012', 'Family Law, Indigenous Peoples Rights, Environmental Law', 'Kiangan, Ifugao', 14],
            ['Atty. Eduardo Santos', 'eduardo.santos@email.com, +63 933 789 0123', 'Family Law, Land Rights, Customary Law', 'Bataraza, Palawan', 11],
            ['Atty. Sofia Garcia', 'sofia.garcia@email.com, +63 977 890 1234', 'Family Law, Tribal Law, Alternative Dispute Resolution', 'Batanes, CAR', 10],
            ['Atty. Juanita Reyes', 'juanita.reyes@email.com, +63 955 901 2345', 'Family Law, Customary Law, Indigenous Justice Systems', 'Lamitan, Basilan', 9],
            ['Atty. Maria Santos', 'maria.santos@email.com, +63 912 345 6789', 'Family Law, Civil Law, Indigenous Peoples Rights', 'Banaue, Ifugao', 9]
        ];

        foreach ($lawyers as $lawyer) {
            list($name, $contact, $specializations, $location, $experience) = $lawyer;

            // $contactArray = explode(', ', $contact);
            // $email = $contactArray[0];
            // $phone = $contactArray[1];

            Lawyers::create([
                'name' => $name,
                'contact' => $contact,
                'specializations' => $specializations,
                'location' => $location,
                'experience' => $experience
            ]);
        }
    }
}
