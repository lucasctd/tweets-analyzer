#map bolsonaro's followers
source("shared/users_map.r")

renderUsersMap(c('alckmin', 'geraldoalckmin', 'alckmin2018', 'alckminpresidente', 'geraldoalckminpresidente'), 
               c(0, 10, 50, 300), c("0 - 10", "10 - 50", "50 - 300"), 'Geraldo Alckmin')
