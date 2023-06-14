read -p "Enter the date: " date

cp sample.xhtml art/$date.xhtml

awk -v var="$date" '{gsub(/\<date\>/,var)}1' sample.xhtml > art/$date.xhtml

read -p "Enter news (seperated with commas): " news

list_items="<li>$(echo "$news" | sed 's/,/<\/li><li>/g')</li>"

awk -v var="$list_items" '/<ul>/ && !done {print $0 var; done=1; next} 1' art/$date.xhtml > temp.xhtml
cat temp.xhtml
rm -rf art/$date
mv temp.xhtml art/$date.xhtml

echo "Finished"
