#map bolsonaro's followers
source("shared/followers_map.r")
#c(0, 10, 150, 300)
#c("0 - 10", "10 - 150", "150 - 300")
getFollowersMap('alckmin', 'geraldoalckmin', 'alckmin2018', c(0, 10, 150, 300), c("0 - 10", "10 - 150", "150 - 300"), 'Geraldo Alckmin')
