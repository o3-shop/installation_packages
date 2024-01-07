# O3-Shop Pakete zur Installation ohne Composer

Der O3-Shop verwendet bei der Installation zur Paketzusammenstellung Composer. Dies ist technisch zwar etwas anspruchsvoller, hat jedoch handfeste Vorteile. Möchtest Du begründet auf den Einsatz von Composer verzichten, stellen wir Dir hier vorkompilierte Shoppakete zur Verfügung. Beachte jedoch bitte auch die Gründe, warum Du auf Composer nicht verzichten solltest.

## composerfreie Shopinstallation

Finde Deine gewünschte Shopversion auf der (//releases) Releasesseite. Lade Dir das Installationspaket herunter und entpacke es im Zielverzeichnis Deines Shops.

Stelle den Document Root Deiner Installation auf das "source"-Verzeichnis ein und folge unserer allgemeinen Installationsanleitung.

## Warum solltest Du dennoch Composer zur Installation verwenden?

- Diese vorkompilierten Pakete können schon älter sein. Nachträgliche Paketupdates sind darin möglicherweise nicht berücksichtigt. Composer stellt die Pakete mit den jeweils aktuellsten Versionen zum Installationszeitpunkt zusammen. Damit sind diese Installationen üblicherweise fehlerfreier.
- Wir stellen die Pakete für bestimmte Serverkonstellationen zusammen. Das muss jedoch nicht Deinem Server entsprechen. Composer kann auf individuelle Einstellungen Rücksicht nehmen und flexibel optimalere Pakete auswählen. Composerinstallationen sind damit besser auf Deinen Server abgestimmt.
- Die manuelle Updateprüfung wird bei der Menge an verwendeten Paketen aufwändig. Composer unterstützt Dich dabei mit einem einzigen Aufruf und kann aktuellere Pakete auch gleich installieren.
- Module werden typischerweise für Composerinstallation bereitgestellt. Die Installation ohne diesen kann schwierig werden.

Wenn Du Fragen hast oder Unterstützung bei der Verwendung von Composer benötigst, melde Dich bitte bei uns.
